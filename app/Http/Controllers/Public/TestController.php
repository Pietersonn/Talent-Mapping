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
            'event_id'   => 'nullable|exists:program,id',
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
                return back()->withErrors(['event_id' => 'Anda sudah menyelesaikan program ini. Silakan pilih yang lain.'])->withInput();
            }
        }

        $existingActive = TestSession::where('id_pengguna', Auth::id())
            ->where('selesai', false)
            ->first();

        if ($existingActive) {
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

    public function tkPage(int $page): View|RedirectResponse
    {
        return $this->showTKPage(request(), $page);
    }

    public function storeTKPage(Request $request, int $page)
    {
        return $this->processTKPage($request, $page);
    }

    /** Show ST-30 stage */
    public function showST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Selesaikan langkah sebelumnya terlebih dahulu.');
        }

        $activeVersion = QuestionVersion::where('jenis', 'st30')->where('aktif', true)->first();
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

        $progress = $this->calculateProgress($session->langkah_saat_ini);

        return view('public.test.st30.stage', compact('session', 'stage', 'availableQuestions', 'progress'));
    }

    /** Process ST-30 stage */
    public function processST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Sesi tidak valid.');
        }

        $activeVersion = QuestionVersion::where('jenis', 'st30')->where('aktif', true)->first();

        $request->validate([
            'selected_questions'   => 'required|array|min:5|max:7',
            'selected_questions.*' => 'required|exists:soal_st30,id',
        ]);

        $existingResponse = ST30Response::where('id_sesi', $session->id)
            ->where('nomor_tahap', (int)$stage)
            ->first();

        $payload = [
            'item_dipilih'    => $request->selected_questions,
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
                'item_dipilih'    => $request->selected_questions,
                'untuk_penilaian' => in_array((int)$stage, [1, 2], true),
            ]);
        }

        if ((int)$stage < 4) {
            $nextStage = (int)$stage + 1;
            $session->update(['langkah_saat_ini' => 'st30_stage' . $nextStage]);

            return redirect()->route('test.st30.stage', ['stage' => $nextStage])
                ->with('success', "Stage {$stage} selesai!");
        }

        $session->update(['langkah_saat_ini' => 'tk_page1']);

        return redirect()->route('test.tk.page', ['page' => 1]);
    }

    /** Show TK page */
    public function showTKPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "tk_page{$page}")) {
            return redirect()->route('test.form')->with('error', 'Selesaikan langkah sebelumnya terlebih dahulu.');
        }

        $activeVersion = QuestionVersion::where('jenis', 'tk')->where('aktif', true)->first();
        if (!$activeVersion) {
            return redirect()->route('test.form')->with('error', 'Tidak ada versi TK aktif.');
        }

        $startNumber = (($page - 1) * 10) + 1;
        $endNumber   = $page * 10;

        // --- INILAH KUNCI PERBAIKANNYA ---
        // Tambahkan with(['options' => ...]) agar tabel pilihan ditarik.
        // Beri juga filter aktif = true agar opsi yang tidak aktif tidak muncul.
        $questions = TalentCompetencyQuestion::with(['options' => function ($query) {
            $query->where('aktif', true)->orderBy('huruf_pilihan', 'asc');
        }])
            ->where('id_versi', $activeVersion->id)
            ->where('aktif', true)
            ->whereBetween('nomor', [$startNumber, $endNumber])
            ->orderBy('nomor')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('test.form')->with('error', 'Tidak ada pertanyaan pada halaman ini.');
        }

        $existingResponses = TalentCompetencyResponse::where('id_sesi', $session->id)
            ->where('nomor_halaman', $page)
            ->get()
            ->keyBy('id_soal');

        $progress = $this->calculateProgress($session->langkah_saat_ini);

        return view('public.test.tk.page', compact(
            'session',
            'page',
            'questions',
            'existingResponses',
            'progress',
            'activeVersion'
        ));
    }

    /** Process TK page */
    public function processTKPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$this->canAccessStage($session, "tk_page{$page}")) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi tidak valid / akses halaman tidak sah.'], 403);
            }
            return redirect()->route('test.form')->with('error', 'Sesi tidak valid / akses halaman tidak sah.');
        }

        $activeVersion = QuestionVersion::where('jenis', 'tk')->where('aktif', true)->first();
        if (!$activeVersion) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tidak ada versi TK aktif.'], 500);
            }
            return redirect()->route('test.form')->with('error', 'Tidak ada versi TK aktif.');
        }

        $startNumber = (($page - 1) * 10) + 1;
        $endNumber   = $page * 10;

        $questions = TalentCompetencyQuestion::where('id_versi', $activeVersion->id)
            ->whereBetween('nomor', [$startNumber, $endNumber])
            ->get();

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
                ->value('id') ?? $this->generateResponseId('TKR');

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

        $lastPageNumber = 5;
        if ($page < $lastPageNumber) {
            $next = $page + 1;

            $session->update(['langkah_saat_ini' => 'tk_page' . $next]);
            $nextUrl = route('test.tk.page', ['page' => $next]);

            if ($request->expectsJson()) {
                return response()->json(['next' => $nextUrl], 200);
            }
            return redirect()->route('test.tk.page', ['page' => $next])->with('success', "Halaman {$page} selesai!");
        }

        // --- TES SELESAI ---
        $session->update([
            'langkah_saat_ini'  => 'thanks',
            'selesai'           => true,
            'diselesaikan_pada' => now(),
        ]);

        try {
            ScoringHelper::calculateAndSaveResults($session->id);
        } catch (\Throwable $e) {
            Log::error('ScoringHelper failed: ' . $e->getMessage(), ['session' => $session->id]);
        }

        try {
            GenerateAssessmentReport::dispatch($session->id);
        } catch (\Throwable $e) {
            Log::error('Failed to dispatch report generation: ' . $e->getMessage());
        }

        $nextUrl = route('test.thank-you');

        if ($request->expectsJson()) {
            return response()->json(['next' => $nextUrl], 200);
        }

        return redirect()->route('test.thank-you')->with('success', 'Tes berhasil diselesaikan!');
    }

    public function completed(): View|RedirectResponse
    {
        $session = $this->getCurrentSession(request());

        if (!$session || !$session->selesai) {
            return redirect()->route('test.form')
                ->with('error', 'Tidak ditemukan sesi tes yang selesai.');
        }

        return view('public.test.completed', compact('session'));
    }

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

    private function calculateProgress(string $currentStep): float
    {
        $progressMap = [
            'form_data'   => 0,
            'st30_stage1' => 33,
            'st30_stage2' => 40,
            'st30_stage3' => 47,
            'st30_stage4' => 55,
            'tk_page1'    => 63,
            'tk_page2'    => 72,
            'tk_page3'    => 78,
            'tk_page4'    => 82,
            'tk_page5'    => 88,
            'thanks'      => 100,
            'completed'   => 100,
        ];
        return $progressMap[$currentStep] ?? 0;
    }

    private function getQuestionsExcludingStages(string $versionId, TestSession $session, array $excludeStages): Collection
    {
        $excludedIds = QuestionHelper::getSelectedQuestionIds($session, $excludeStages);

        return ST30Question::where('id_versi', $versionId)
            ->where('aktif', true)
            ->whereNotIn('id', $excludedIds)
            ->get();
    }

    private function getCurrentSession(Request $request): ?TestSession
    {
        $sessionToken = $request->session()->get('test_session_token');
        if (!$sessionToken) return null;

        return TestSession::where('token_sesi', $sessionToken)->first();
    }

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
            'tk_page1'    => redirect()->route('test.tk.page', ['page' => 1]),
            'tk_page2'    => redirect()->route('test.tk.page', ['page' => 2]),
            'tk_page3'    => redirect()->route('test.tk.page', ['page' => 3]),
            'tk_page4'    => redirect()->route('test.tk.page', ['page' => 4]),
            'tk_page5'    => redirect()->route('test.tk.page', ['page' => 5]),
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
