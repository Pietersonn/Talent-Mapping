<?php

namespace App\Http\Controllers\Public;

use Illuminate\Routing\Controller as BaseController;
use App\Models\TestSession;
use App\Models\ST30Question;
use App\Models\ST30Response;
use App\Models\TalentCompetencyQuestion;
use App\Models\TalentCompetencyResponse;
use App\Models\QuestionVersion;
use App\Models\Program;
use App\Helpers\QuestionHelper;
use App\Helpers\ScoringHelper;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $activePrograms = Program::where('aktif', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->get();

        return view('public.test.form', compact('activePrograms'));
    }

    /** Store test form submission */
    public function storeForm(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'workplace'  => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'event_id'   => 'nullable|string', // Validasi diperlonggar agar tidak error 302
            'event_code' => 'nullable|string|max:50',
        ]);

        $program = null;
        if ($request->filled('event_id')) {
            $program = Program::where('id', $request->event_id)
                ->where('aktif', true)
                ->first();

            if (!$program) {
                return back()->withErrors(['event_id' => 'Program tidak aktif / tidak ditemukan.'])->withInput();
            }

            if (!$request->filled('event_code') || strtoupper($request->event_code) !== strtoupper($program->kode_program)) {
                return back()->withErrors(['event_code' => 'Kode program tidak sesuai.'])->withInput();
            }

            $alreadyFinishedSameEvent = TestSession::where('id_pengguna', Auth::id())
                ->where('id_program', $program->id)
                ->where('selesai', true)
                ->exists();

            if ($alreadyFinishedSameEvent) {
                return back()->withErrors(['event_id' => 'Anda sudah menyelesaikan program ini. Silakan pilih program lain.'])->withInput();
            }
        }

        $existingActive = TestSession::where('id_pengguna', Auth::id())
            ->where('selesai', false)
            ->first();

        if ($existingActive) {
            $existingActive->update([
                'id_program'     => $program?->id,
                'nama_peserta'   => $request->full_name,
                'latar_belakang' => $request->workplace,
                'jabatan'        => $request->position,
            ]);

            if ($program) {
                $program->participants()->syncWithoutDetaching([
                    Auth::id() => ['tes_selesai' => false, 'hasil_terkirim' => false],
                ]);
            }
            return $this->redirectToCurrentStep($existingActive);
        }

        $testSession = TestSession::create([
            'id_pengguna'      => Auth::id(),
            'id_program'       => $program?->id,
            'token_sesi'       => Str::random(32),
            'nama_peserta'     => $request->full_name,
            'latar_belakang'   => $request->workplace,
            'jabatan'          => $request->position,
            'langkah_saat_ini' => 'st30_stage1',
            'selesai'          => false,
        ]);

        session(['test_session_token' => $testSession->token_sesi]);

        if ($program) {
            $program->participants()->syncWithoutDetaching([
                Auth::id() => [
                    'tes_selesai'    => false,
                    'hasil_terkirim' => false,
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
    public function storeSJTPage(Request $request, int $page)
    {
        return $this->processSJTPage($request, $page);
    }

    /** Show ST-30 stage */
    public function showST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Selesaikan langkah sebelumnya terlebih dahulu.');
        }

        $activeVersion = QuestionVersion::where('tipe', 'st30')->where('aktif', true)->first();
        if (!$activeVersion) {
            return redirect()->route('test.form')->with('error', 'Tidak ada versi ST-30 aktif.');
        }

        $availableQuestions = match ((int)$stage) {
            1 => ST30Question::where('id_versi', $activeVersion->id)->where('aktif', true)->get(),
            2 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1]),
            3 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1, 2]),
            4 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1, 2, 3]),
            default => collect()
        };

        if ($availableQuestions->isEmpty()) {
            return redirect()->route('test.form')->with('error', 'Tidak ada pertanyaan untuk stage ini.');
        }

        $answeredIds = [];
        $existingResponse = ST30Response::where('id_sesi', $session->id)->where('nomor_tahap', $stage)->first();
        if($existingResponse && $existingResponse->item_dipilih){
             $decoded = json_decode($existingResponse->item_dipilih, true);
             if(is_array($decoded)) {
                 $answeredIds = array_flip($decoded);
             }
        }

        $progress = $this->calculateProgress($session->langkah_saat_ini);

        return view('public.test.st30.stage', compact('session', 'stage', 'availableQuestions', 'progress', 'answeredIds'));
    }

    /** Process ST-30 stage */
    public function processST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Sesi tidak valid / akses tahap tidak sah.');
        }

        $activeVersion = QuestionVersion::where('tipe', 'st30')->where('aktif', true)->first();

        $request->validate([
            'selected_questions'   => 'required|array|min:5|max:7',
            'selected_questions.*' => 'required|exists:soal_st30,id',
        ]);

        $alreadyPicked = match ((int)$stage) {
            2 => $this->getSelectedQuestionIds($session, [1]),
            3 => $this->getSelectedQuestionIds($session, [1, 2]),
            4 => $this->getSelectedQuestionIds($session, [1, 2, 3]),
            default => [],
        };

        foreach ($request->selected_questions as $qid) {
            if (in_array($qid, $alreadyPicked)) {
                return back()->with('error', 'Tidak boleh memilih soal yang sama dari tahap sebelumnya.');
            }
        }

        $existingResponse = ST30Response::where('id_sesi', $session->id)
            ->where('nomor_tahap', (int)$stage)
            ->first();

        $payload = [
            'item_dipilih'    => json_encode($request->selected_questions),
            'untuk_penilaian' => in_array((int)$stage, [1, 2], true),
        ];

        if ($existingResponse) {
            $existingResponse->update($payload);
        } else {
            ST30Response::create([
                'id'              => $this->generateResponseId('ST30'),
                'id_sesi'         => $session->id,
                'id_versi_soal'   => $activeVersion->id,
                'nomor_tahap'     => (int)$stage,
                'item_dipilih'    => json_encode($request->selected_questions),
                'untuk_penilaian' => in_array((int)$stage, [1, 2], true),
            ]);
        }

        $session->update(['id_versi_st30' => $activeVersion->id]);

        if ((int)$stage < 4) {
            $nextStage = (int)$stage + 1;
            $session->update(['langkah_saat_ini' => 'st30_stage' . $nextStage]);

            return redirect()->route('test.st30.stage', ['stage' => $nextStage]);
        }

        $session->update(['langkah_saat_ini' => 'sjt_page1']);
        return redirect()->route('test.tk.page', ['page' => 1]);
    }

    /** Show SJT page */
    public function showSJTPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "sjt_page{$page}")) {
            return redirect()->route('test.form')->with('error', 'Selesaikan langkah sebelumnya terlebih dahulu.');
        }

        $activeVersion = QuestionVersion::where('tipe', 'tk')->where('aktif', true)->first();
        if (!$activeVersion) {
            return redirect()->route('test.form')->with('error', 'Tidak ada versi SJT aktif.');
        }

        $startNumber = (($page - 1) * 10) + 1;
        $endNumber   = $page * 10;

        $questions = TalentCompetencyQuestion::where('id_versi', $activeVersion->id)
            ->where('aktif', true)
            ->whereBetween('nomor', [$startNumber, $endNumber])
            ->orderBy('nomor')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('test.form')->with('error', 'Tidak ada pertanyaan pada halaman ini.');
        }

        $answeredArray = TalentCompetencyResponse::where('id_sesi', $session->id)
            ->where('nomor_halaman', $page)
            ->get();

        $answered = [];
        foreach($answeredArray as $ans) {
            $answered[$ans->id_soal] = $ans->pilihan_dipilih;
        }

        $progress = $this->calculateProgress($session->langkah_saat_ini);
        $totalPages = ceil(TalentCompetencyQuestion::where('id_versi', $activeVersion->id)->count() / 10);
        $tkVersion = $activeVersion;

        return view('public.test.tk.page', compact(
            'session',
            'page',
            'questions',
            'answered',
            'progress',
            'totalPages',
            'tkVersion'
        ));
    }

    /** Process SJT page */
    public function processSJTPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "sjt_page{$page}")) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi tidak valid / akses halaman tidak sah.'], 403);
            }
            return redirect()->route('test.form')->with('error', 'Sesi tidak valid / akses halaman tidak sah.');
        }

        $activeVersion = QuestionVersion::where('tipe', 'tk')->where('aktif', true)->first();
        if (!$activeVersion) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tidak ada versi SJT aktif.'], 500);
            }
            return redirect()->route('test.form')->with('error', 'Tidak ada versi SJT aktif.');
        }

        $startNumber = (($page - 1) * 10) + 1;
        $endNumber   = $page * 10;

        $questions = TalentCompetencyQuestion::where('id_versi', $activeVersion->id)
            ->whereBetween('nomor', [$startNumber, $endNumber])
            ->get();

        if ($request->has('answers')) {
            $responses = [];
            foreach ($request->input('answers') as $ans) {
                $responses[$ans['question_id']] = $ans['option_id'];
            }
            $request->merge(['responses' => $responses]);
        }

        $rules = [];
        foreach ($questions as $q) {
            $rules["responses.{$q->id}"] = ['required', 'in:a,b,c,d,e'];
        }
        $messages = [
            'responses.*.required' => 'Semua pertanyaan wajib dijawab.',
            'responses.*.in'       => 'Pilihan tidak valid.'
        ];

        $request->validate($rules, $messages);

        $responsesInput = $request->input('responses', []);
        $now = now();

        foreach ($questions as $q) {
            $sel = $responsesInput[$q->id] ?? null;
            $responseId = TalentCompetencyResponse::where('id_sesi', $session->id)
                ->where('id_soal', $q->id)
                ->value('id') ?? $this->generateResponseId('SJR');

            TalentCompetencyResponse::updateOrCreate(
                ['id' => $responseId],
                [
                    'id_sesi'         => $session->id,
                    'id_soal'         => $q->id,
                    'id_versi_soal'   => $activeVersion->id,
                    'nomor_halaman'   => $page,
                    'pilihan_dipilih' => $sel,
                    'updated_at'      => $now,
                    'created_at'      => $now
                ]
            );
        }

        $lastPageNumber = ceil(TalentCompetencyQuestion::where('id_versi', $activeVersion->id)->count() / 10);
        if ($page < $lastPageNumber) {
            $next = $page + 1;
            $session->update(['langkah_saat_ini' => 'sjt_page' . $next]);

            $nextUrl = route('test.tk.page', ['page' => $next]);

            if ($request->expectsJson()) {
                return response()->json(['redirect' => $nextUrl], 200);
            }
            return redirect()->route('test.tk.page', ['page' => $next])->with('success', "Halaman {$page} selesai!");
        }

        // --- TES SELESAI ---
        $session->update([
            'langkah_saat_ini' => 'thanks',
            'selesai'          => true,
            'selesai_pada'     => now(),
        ]);

        if ($session->program) {
            $session->program->participants()->updateExistingPivot(
                $session->id_pengguna,
                ['tes_selesai' => true]
            );
        }

        try {
            ScoringHelper::calculateAndSaveResults($session->id);
        } catch (\Throwable $e) {
            Log::error('ScoringHelper failed: ' . $e->getMessage());
        }

        try {
            GenerateAssessmentReport::dispatch($session->id);
        } catch (\Throwable $e) {
            Log::error('Failed to dispatch report generation: ' . $e->getMessage());
        }

        $nextUrl = route('test.thank-you');

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $nextUrl], 200);
        }

        return redirect()->route('test.thank-you')->with('success', 'Tes berhasil diselesaikan! Hasil Anda sedang diproses.');
    }

    /** Completed page */
    public function completed(): View|RedirectResponse
    {
        $session = $this->getCurrentSession(request());

        if (!$session || !$session->selesai) {
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

        if (!in_array($session->langkah_saat_ini, ['thanks', 'completed']) && !$session->selesai) {
            return redirect()->route('test.form')->with('error', 'Selesaikan assessment terlebih dahulu.');
        }

        return view('public.test.thank-you', compact('session'));
    }

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
        $excludedIds = $this->getSelectedQuestionIds($session, $excludeStages);

        return ST30Question::where('id_versi', $versionId)
            ->where('aktif', true)
            ->whereNotIn('id', $excludedIds)
            ->orderBy('nomor')
            ->get();
    }

    private function getSelectedQuestionIds(TestSession $session, array $excludeStages): array
    {
        $responses = ST30Response::where('id_sesi', $session->id)
            ->whereIn('nomor_tahap', $excludeStages)
            ->get();

        $excluded = [];
        foreach ($responses as $resp) {
            $items = json_decode($resp->item_dipilih, true) ?: [];
            $excluded = array_merge($excluded, $items);
        }
        return array_unique($excluded);
    }

    /** Current test session from session token */
    private function getCurrentSession(Request $request): ?TestSession
    {
        $sessionToken = $request->session()->get('test_session_token');

        if (!$sessionToken) {
            $uncompletedSession = TestSession::where('id_pengguna', Auth::id())
                ->where('selesai', false)
                ->first();

            if($uncompletedSession) {
                session(['test_session_token' => $uncompletedSession->token_sesi]);
                return $uncompletedSession;
            }

            return null;
        }

        return TestSession::where('token_sesi', $sessionToken)->first();
    }

    /** Generator ID respons ST30/SJT yang konsisten */
    private function generateResponseId(string $prefix): string
    {
        do {
            $id = $prefix . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (
            ST30Response::where('id', $id)->exists() ||
            TalentCompetencyResponse::where('id', $id)->exists()
        );

        return $id;
    }

    private function redirectToCurrentStep(TestSession $session): RedirectResponse
    {
        session(['test_session_token' => $session->token_sesi]);

        return match ($session->langkah_saat_ini) {
            'form_data', 'st30_stage1' => redirect()->route('test.st30.stage', ['stage' => 1]),
            'st30_stage2' => redirect()->route('test.st30.stage', ['stage' => 2]),
            'st30_stage3' => redirect()->route('test.st30.stage', ['stage' => 3]),
            'st30_stage4' => redirect()->route('test.st30.stage', ['stage' => 4]),
            'sjt_page1'   => redirect()->route('test.tk.page', ['page' => 1]),
            'sjt_page2'   => redirect()->route('test.tk.page', ['page' => 2]),
            'sjt_page3'   => redirect()->route('test.tk.page', ['page' => 3]),
            'sjt_page4'   => redirect()->route('test.tk.page', ['page' => 4]),
            'sjt_page5'   => redirect()->route('test.tk.page', ['page' => 5]),
            'thanks'      => redirect()->route('test.thank-you'),
            'completed'   => redirect()->route('test.completed'),
            default       => redirect()->route('test.st30.stage', ['stage' => 1]),
        };
    }

    private function canAccessStage(?TestSession $session, string $targetStep): bool
    {
        return $session && $session->langkah_saat_ini === $targetStep;
    }
}
