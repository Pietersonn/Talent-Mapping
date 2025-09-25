<?php

namespace App\Http\Controllers\Public;

use Illuminate\Routing\Controller as BaseController;
use App\Models\TestSession;
use App\Models\ST30Question;
use App\Models\ST30Response;
use App\Models\SJTQuestion;
use App\Models\SJTResponse;
use App\Models\QuestionVersion;
use App\Models\Event;
use App\Models\TestResult;
use App\Helpers\QuestionHelper;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Jobs\GenerateAssessmentReport;

class TestController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Show test registration form */
    public function form(): View
    {
        $activeEvents = Event::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('public.test.form', compact('activeEvents'));
    }

    /** Store test form submission */
    public function storeForm(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'workplace'  => 'required|string|max:255',
            'event_id'   => 'nullable|exists:events,id',
            'event_code' => 'nullable|string|max:50',
        ]);

        // Pilih event → validasi aktif + cocokan kode (kode tidak di dropdown)
        $event = null;
        if ($request->filled('event_id')) {
            $event = Event::where('id', $request->event_id)
                ->where('is_active', true)
                ->first();

            if (!$event) {
                return back()->withErrors(['event_id' => 'Event tidak aktif / tidak ditemukan.'])->withInput();
            }

            if (!$request->filled('event_code') || strtoupper($request->event_code) !== strtoupper($event->event_code)) {
                return back()->withErrors(['event_code' => 'Event code tidak sesuai.'])->withInput();
            }

            // Cegah daftar ulang event yang sama jika sudah selesai
            $alreadyFinishedSameEvent = TestSession::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->where('is_completed', true)
                ->exists();

            if ($alreadyFinishedSameEvent) {
                return back()->withErrors(['event_id' => 'Anda sudah menyelesaikan event ini. Silakan pilih event lain.'])->withInput();
            }
        }

        // Jika masih ada sesi aktif (event apa pun) → lanjutkan
        $existingActive = TestSession::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->first();

        if ($existingActive) {
            return $this->redirectToCurrentStep($existingActive);
        }

        // Buat sesi baru
        $testSession = TestSession::create([
            'id'                     => $this->generateTestSessionId(),
            'user_id'                => Auth::id(),
            'event_id'               => $event?->id,
            'session_token'          => Str::random(32),
            'participant_name'       => $request->full_name,
            'participant_background' => $request->workplace,
            'current_step'           => 'st30_stage1',
        ]);

        session(['test_session_token' => $testSession->session_token]);

        if ($event) {
            $event->participants()->syncWithoutDetaching([
                Auth::id() => [
                    'test_completed' => false,
                    'results_sent'   => false,
                ],
            ]);
        }

        return redirect()->route('test.st30.stage', ['stage' => 1])
            ->with('success', 'Sesi tes dimulai!');
    }

    /** Aliases */
    public function st30Stage(int $stage): View|RedirectResponse
    {
        return $this->showST30Stage(request(), $stage);
    }
    public function storeST30Stage(Request $request, int $stage): RedirectResponse
    {
        return $this->processST30Stage($request, $stage);
    }
    public function sjtPage(int $page): View|RedirectResponse
    {
        return $this->showSJTPage(request(), $page);
    }
    public function storeSJTPage(Request $request, int $page): RedirectResponse
    {
        return $this->processSJTPage($request, $page);
    }

    /** Show ST-30 stage */
    public function showST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);
        if (!$session || !$session->canAccessStage("st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Selesaikan langkah sebelumnya terlebih dahulu.');
        }

        $activeVersion = QuestionVersion::where('type', 'st30')->where('is_active', true)->first();
        if (!$activeVersion) {
            return redirect()->route('test.form')->with('error', 'Tidak ada versi ST-30 aktif.');
        }

        $availableQuestions = match ((int)$stage) {
            1 => ST30Question::where('version_id', $activeVersion->id)->where('is_active', true)->get(),
            2 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1]),
            3 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1, 2]),
            4 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1, 2, 3]),
            default => collect()
        };

        if ($availableQuestions->isEmpty()) {
            return redirect()->route('test.form')->with('error', 'Tidak ada pertanyaan untuk stage ini.');
        }

        $progress = $this->calculateProgress($session->current_step);

        return view('public.test.st30.stage', compact('session', 'stage', 'availableQuestions', 'progress'));
    }

    /** Process ST-30 stage */
    public function processST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);
        if (!$session || !$session->canAccessStage("st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Sesi tidak valid / akses tahap tidak sah.');
        }

        $activeVersion = QuestionVersion::where('type', 'st30')->where('is_active', true)->first();

        $request->validate([
            'selected_questions'   => 'required|array|min:5|max:7',
            'selected_questions.*' => 'required|exists:st30_questions,id',
        ]);

        $alreadyPicked = match ((int)$stage) {
            2 => QuestionHelper::getSelectedQuestionIds($session, [1]),
            3 => QuestionHelper::getSelectedQuestionIds($session, [1, 2]),
            4 => QuestionHelper::getSelectedQuestionIds($session, [1, 2, 3]),
            default => [],
        };

        foreach ($request->selected_questions as $qid) {
            if (in_array($qid, $alreadyPicked)) {
                return back()->with('error', 'Tidak boleh memilih soal yang sama dari tahap sebelumnya.');
            }
        }

        $existingResponse = ST30Response::where('session_id', $session->id)
            ->where('stage_number', (int)$stage)
            ->first();

        $payload = [
            'selected_items' => $request->selected_questions,
            'for_scoring'    => in_array((int)$stage, [1, 2], true), // Stage 1 & 2 skoring
        ];

        if ($existingResponse) {
            $existingResponse->update($payload);
        } else {
            ST30Response::create([
                'id'                   => $this->generateResponseId('ST30'),
                'session_id'           => $session->id,
                'question_version_id'  => $activeVersion->id,
                'stage_number'         => (int)$stage,
                'selected_items'       => $request->selected_questions,
                'for_scoring'          => in_array((int)$stage, [1, 2], true),
            ]);
        }

        if ((int)$stage < 4) {
            $nextStage = (int)$stage + 1;
            $session->update(['current_step' => 'st30_stage' . $nextStage]);

            return redirect()->route('test.st30.stage', ['stage' => $nextStage])
                ->with('success', "Stage {$stage} selesai!");
        }

        $session->update(['current_step' => 'sjt_page1']);

        return redirect()->route('test.sjt.page', ['page' => 1])
            ->with('success', 'ST-30 selesai! Lanjut ke SJT.');
    }

    /** Show SJT page */
    public function showSJTPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);
        if (!$session || !$session->canAccessStage("sjt_page{$page}")) {
            return redirect()->route('test.form')->with('error', 'Selesaikan langkah sebelumnya terlebih dahulu.');
        }

        $activeVersion = QuestionVersion::where('type', 'sjt')->where('is_active', true)->first();
        if (!$activeVersion) {
            return redirect()->route('test.form')->with('error', 'Tidak ada versi SJT aktif.');
        }

        $startNumber = (($page - 1) * 10) + 1;
        $endNumber   = $page * 10;

        $questions = SJTQuestion::where('version_id', $activeVersion->id)
            ->where('is_active', true)
            ->whereBetween('number', [$startNumber, $endNumber])
            ->orderBy('number')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('test.form')->with('error', 'Tidak ada pertanyaan pada halaman ini.');
        }

        $questions = $questions->load(['options' => function ($query) {
            $query->where('is_active', true)->orderBy('option_letter');
        }]);

        $existingResponses = SJTResponse::where('session_id', $session->id)
            ->where('page_number', $page)
            ->get()
            ->keyBy('question_id');

        $progress = $this->calculateProgress($session->current_step);

        return view('public.test.sjt.page', compact(
            'session',
            'page',
            'questions',
            'existingResponses',
            'progress'
        ));
    }

    /** Process SJT page */
    public function processSJTPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);
        if (!$session || !$session->canAccessStage("sjt_page{$page}")) {
            return redirect()->route('test.form')->with('error', 'Sesi tidak valid / akses halaman tidak sah.');
        }

        $activeVersion = QuestionVersion::where('type', 'sjt')->where('is_active', true)->first();

        $startNumber = (($page - 1) * 10) + 1;
        $endNumber   = $page * 10;

        $questions = SJTQuestion::where('version_id', $activeVersion->id)
            ->whereBetween('number', [$startNumber, $endNumber])
            ->get();

        $rules = [];
        foreach ($questions as $q) {
            $rules["responses.{$q->id}"] = ['required', 'in:a,b,c,d,e'];
        }
        $request->validate($rules, [
            'responses.*.required' => 'Semua pertanyaan wajib dijawab.',
            'responses.*.in'       => 'Pilihan tidak valid.',
        ]);

        foreach ($questions as $q) {
            $opt = $request->input("responses.{$q->id}");
            SJTResponse::updateOrCreate(
                ['session_id' => $session->id, 'question_id' => $q->id],
                [
                    'question_version_id' => $activeVersion->id,
                    'page_number'         => $page,
                    'selected_option'     => $opt,
                ]
            );
        }

        if ($page < 5) {
            $next = $page + 1;
            $session->update(['current_step' => 'sjt_page' . $next]);
            return redirect()->route('test.sjt.page', ['page' => $next])
                ->with('success', "Halaman {$page} selesai!");
        }

        // Halaman 5 selesai → kirim ke thank-you & jalankan job
        $session->update(['current_step' => 'thanks']);
        GenerateAssessmentReport::dispatch($session->id);

        return redirect()->route('test.thank-you')
            ->with('success', 'Terima kasih! Hasil kamu sedang diproses & akan dikirim ke email.');
    }

    /** Completed page */
    public function completed(): View|RedirectResponse
    {
        $session = $this->getCurrentSession(request());

        if (!$session || !$session->is_completed) {
            return redirect()->route('test.form')
                ->with('error', 'Tidak ditemukan sesi tes yang selesai.');
        }

        return view('public.test.completed', compact('session'));
    }

    /** Thank-you page */
    public function thankYou(): View|RedirectResponse
    {
        $session = $this->getCurrentSession(request());
        if (!$session) {
            return redirect()->route('test.form')->with('error', 'Tidak ada sesi aktif.');
        }

        if (!in_array($session->current_step, ['thanks', 'completed']) && !$session->is_completed) {
            return redirect()->route('test.form')->with('error', 'Selesaikan assessment terlebih dahulu.');
        }

        return view('public.test.thank-you', compact('session'));
    }

    /** Progress bar */
    /** Progress bar */
    private function calculateProgress(string $currentStep): float
    {
        $progressMap = [
            'form_data'   => 0,
            'st30_stage1' => 33,
            'st30_stage2' => 40,
            'st30_stage3' => 47,
            'st30_stage4' => 55,
            'sjt_page1'   => 63,
            'sjt_page2'   => 72,
            'sjt_page3'   => 78,
            'sjt_page4'   => 82,
            'sjt_page5'   => 88,
            'thanks'      => 100,
            'completed'   => 100,
        ];
        return $progressMap[$currentStep] ?? 0;
    }


    /** Get questions excluding previous picks */
    private function getQuestionsExcludingStages(string $versionId, TestSession $session, array $excludeStages): Collection
    {
        $excludedIds = QuestionHelper::getSelectedQuestionIds($session, $excludeStages);

        return ST30Question::where('version_id', $versionId)
            ->where('is_active', true)
            ->whereNotIn('id', $excludedIds)
            ->get();
    }

    /** Current test session from session token */
    private function getCurrentSession(Request $request): ?TestSession
    {
        $sessionToken = $request->session()->get('test_session_token');
        if (!$sessionToken) return null;

        return TestSession::where('session_token', $sessionToken)->first();
    }

    private function generateResponseId(string $prefix): string
    {
        do {
            $id = $prefix . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (
            ST30Response::where('id', $id)->exists() ||
            SJTResponse::where('id', $id)->exists()
        );

        return $id;
    }

    private function redirectToCurrentStep(TestSession $session): RedirectResponse
    {
        session(['test_session_token' => $session->session_token]);

        return match ($session->current_step) {
            'form_data', 'st30_stage1' => redirect()->route('test.st30.stage', ['stage' => 1]),
            'st30_stage2' => redirect()->route('test.st30.stage', ['stage' => 2]),
            'st30_stage3' => redirect()->route('test.st30.stage', ['stage' => 3]),
            'st30_stage4' => redirect()->route('test.st30.stage', ['stage' => 4]),
            'sjt_page1'   => redirect()->route('test.sjt.page', ['page' => 1]),
            'sjt_page2'   => redirect()->route('test.sjt.page', ['page' => 2]),
            'sjt_page3'   => redirect()->route('test.sjt.page', ['page' => 3]),
            'sjt_page4'   => redirect()->route('test.sjt.page', ['page' => 4]),
            'sjt_page5'   => redirect()->route('test.sjt.page', ['page' => 5]),
            'thanks'      => redirect()->route('test.thank-you'),
            'completed'   => redirect()->route('test.completed'),
            default       => redirect()->route('test.st30.stage', ['stage' => 1]),
        };
    }

    private function getCurrentTestSession(): ?TestSession
    {
        $sessionToken = session('test_session_token');
        if (!$sessionToken) return null;

        return TestSession::where('session_token', $sessionToken)->first();
    }

    private function generateTestSessionId(): string
    {
        do {
            $id = 'TS' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (TestSession::where('id', $id)->exists());

        return $id;
    }

    private function generateTestResultId(): string
    {
        do {
            $id = 'TR' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (TestResult::where('id', $id)->exists());

        return $id;
    }
}
