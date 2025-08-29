<?php

namespace App\Http\Controllers\Public;

use Illuminate\Routing\Controller as BaseController;
use App\Models\TestSession;
use App\Models\ST30Question;
use App\Models\ST30Response;
use App\Models\SJTQuestion;
use App\Models\SJTOption;
use App\Models\SJTResponse;
use App\Models\QuestionVersion;
use App\Models\Event;
use App\Models\TestResult;
use App\Helpers\QuestionHelper;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
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

    /**
     * Show test registration form
     */
    public function form(): View
    {
        $activeEvents = Event::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('public.test.form', compact('activeEvents'));
    }

    /**
     * Store test form submission
     */
    public function storeForm(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'workplace' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'event_code' => 'nullable|string|max:20'
        ]);

        // Find event if event_id provided
        $event = null;
        if ($request->filled('event_id')) {
            $event = Event::where('id', $request->event_id)
                ->where('is_active', true)
                ->first();

            if (!$event) {
                return back()->withErrors([
                    'event_id' => 'Selected event is not active.'
                ])->withInput();
            }

            // Validate event code if event selected
            if ($request->filled('event_code')) {
                if ($event->event_code !== strtoupper($request->event_code)) {
                    return back()->withErrors([
                        'event_code' => 'Event code does not match.'
                    ])->withInput();
                }
            }
        }

        // Check if user already has active session
        $existingSession = TestSession::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->first();

        if ($existingSession) {
            // Continue existing session based on current step
            return $this->redirectToCurrentStep($existingSession);
        }

        // Create new test session
        $testSession = TestSession::create([
            'id' => $this->generateTestSessionId(),
            'user_id' => Auth::id(),
            'event_id' => $event?->id,
            'session_token' => Str::random(32),
            'participant_name' => $request->full_name,
            'participant_background' => $request->workplace,
            'current_step' => 'st30_stage1',
        ]);

        // Store session token in session
        session(['test_session_token' => $testSession->session_token]);

        // Add user to event participants if event selected
        if ($event) {
            $event->participants()->syncWithoutDetaching([
                Auth::id() => [
                    'test_completed' => false,
                    'results_sent' => false
                ]
            ]);
        }

        return redirect()->route('test.st30.stage', ['stage' => 1])
            ->with('success', 'Test session started successfully!');
    }

    /**
     * Show ST-30 stage (alias untuk showST30Stage)
     */
    public function st30Stage(int $stage): View|RedirectResponse
    {
        return $this->showST30Stage(request(), $stage);
    }

    /**
     * Store ST-30 stage (alias untuk processST30Stage)
     */
    public function storeST30Stage(Request $request, int $stage): RedirectResponse
    {
        return $this->processST30Stage($request, $stage);
    }

    /**
     * Show SJT page (alias untuk showSJTPage)
     */
    public function sjtPage(int $page): View|RedirectResponse
    {
        return $this->showSJTPage(request(), $page);
    }

    /**
     * Store SJT page (alias untuk processSJTPage)
     */
    public function storeSJTPage(Request $request, int $page): RedirectResponse
    {
        return $this->processSJTPage($request, $page);
    }

    /**
     * Show ST-30 stage
     */
    public function showST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$session->canAccessStage("st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Please complete previous steps first.');
        }

        $activeVersion = QuestionVersion::where('type', 'st30')->where('is_active', true)->first();

        if (!$activeVersion) {
            return redirect()->route('test.form')->with('error', 'No active ST-30 version found.');
        }

        // Get available questions based on stage
        $availableQuestions = match ((int)$stage) {
            1 => ST30Question::where('version_id', $activeVersion->id)->where('is_active', true)->get(),
            2 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1]),
            3 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1, 2]),
            4 => $this->getQuestionsExcludingStages($activeVersion->id, $session, [1, 2, 3]),
            default => collect()
        };

        if ($availableQuestions->isEmpty()) {
            return redirect()->route('test.form')->with('error', 'No questions available for this stage.');
        }

        // Calculate progress percentage
        $progress = $this->calculateProgress($session->current_step);

        return view('public.test.st30.stage', compact('session', 'stage', 'availableQuestions', 'progress'));
    }

    /**
     * Process ST-30 stage submission
     */
    public function processST30Stage(Request $request, int $stage)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$session->canAccessStage("st30_stage{$stage}")) {
            return redirect()->route('test.form')->with('error', 'Invalid session or stage access.');
        }

        $activeVersion = QuestionVersion::where('type', 'st30')->where('is_active', true)->first();

        // Validation: 5-7 selections required
        $request->validate([
            'selected_questions' => 'required|array|min:5|max:7',
            'selected_questions.*' => 'required|exists:st30_questions,id',
        ]);

        // Prevent selecting already picked questions
        $alreadyPicked = match ((int)$stage) {
            2 => QuestionHelper::getSelectedQuestionIds($session, [1]),
            3 => QuestionHelper::getSelectedQuestionIds($session, [1, 2]),
            4 => QuestionHelper::getSelectedQuestionIds($session, [1, 2, 3]),
            default => [],
        };

        foreach ($request->selected_questions as $questionId) {
            if (in_array($questionId, $alreadyPicked)) {
                return back()->with('error', 'Cannot select previously chosen questions.');
            }
        }

        // Check if response already exists
        $existingResponse = ST30Response::where('session_id', $session->id)
            ->where('stage_number', (int)$stage)
            ->first();

        // FIXED: Now Stage 1 & 2 are for scoring, Stage 3 & 4 are exploration only
        $payload = [
            'selected_items' => $request->selected_questions,
            'for_scoring' => in_array((int)$stage, [1, 2], true), // FIXED: Stage 1 & 2 for scoring
        ];

        if ($existingResponse) {
            $existingResponse->update($payload);
        } else {
            ST30Response::create([
                'id' => $this->generateResponseId('ST30'),
                'session_id' => $session->id,
                'question_version_id' => $activeVersion->id,
                'stage_number' => (int)$stage,
                'selected_items' => $request->selected_questions,
                'for_scoring' => in_array((int)$stage, [1, 2], true), // FIXED: Stage 1 & 2 for scoring
            ]);
        }

        // Update session progress
        if ((int)$stage < 4) {
            $nextStage = (int)$stage + 1;
            $session->update(['current_step' => 'st30_stage' . $nextStage]);

            return redirect()->route('test.st30.stage', ['stage' => $nextStage])
                ->with('success', 'Stage ' . $stage . ' completed successfully!');
        }

        // ST-30 completed, move to SJT
        $session->update(['current_step' => 'sjt_page1']);

        return redirect()->route('test.sjt.page', ['page' => 1])
            ->with('success', 'ST-30 completed! Moving to SJT assessment.');
    }

    /**
     * Show SJT page
     */
    public function showSJTPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);

        if (!$session || !$session->canAccessStage("sjt_page{$page}")) {
            return redirect()->route('test.form')->with('error', 'Please complete previous steps first.');
        }

        $activeVersion = QuestionVersion::where('type', 'sjt')->where('is_active', true)->first();

        if (!$activeVersion) {
            return redirect()->route('test.form')->with('error', 'No active SJT version found.');
        }

        // Get questions for this page (10 questions per page)
        $startNumber = (($page - 1) * 10) + 1;
        $endNumber = $page * 10;

        $questions = SJTQuestion::where('version_id', $activeVersion->id)
            ->where('is_active', true)
            ->whereBetween('number', [$startNumber, $endNumber])
            ->orderBy('number')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('test.form')->with('error', 'No questions found for this page.');
        }

        // Get options for each question
        $questions = $questions->load(['options' => function ($query) {
            $query->where('is_active', true)->orderBy('option_letter');
        }]);

        // Get existing responses for this page
        $existingResponses = SJTResponse::where('session_id', $session->id)
            ->where('page_number', $page)
            ->get()
            ->keyBy('question_id');

        // Calculate progress percentage
        $progress = $this->calculateProgress($session->current_step);

        return view('public.test.sjt.page', compact(
            'session',
            'page',
            'questions',
            'existingResponses',
            'progress'
        ));
    }

    /**
     * Process SJT page submission
     */
    public function processSJTPage(Request $request, int $page)
    {
        $session = $this->getCurrentSession($request);
        if (!$session || !$session->canAccessStage("sjt_page{$page}")) {
            return redirect()->route('test.form')->with('error', 'Invalid session or page access.');
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
            'responses.*.required' => 'Please answer all questions.',
            'responses.*.in'       => 'Invalid option selected.',
        ]);

        // === SAVE: upsert by (session_id, question_id) TANPA set kolom 'id' ===
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

        // === NAVIGASI ===
        if ($page < 5) {
            $next = $page + 1;
            $session->update(['current_step' => 'sjt_page' . $next]);
            return redirect()->route('test.sjt.page', ['page' => $next])
                ->with('success', "Page {$page} completed successfully!");
        }

        // === PAGE 5 SELESAI → THANKS + JOB ===
        $session->update(['current_step' => 'thanks']); // belum completed; hasil dihitung di Job
        GenerateAssessmentReport::dispatch($session->id);

        return redirect()->route('test.thank-you')
            ->with('success', 'Terima kasih! Hasil kamu sedang diproses & akan dikirim ke email.');
    }

    /**
     * Show completion page
     */
    public function completed(): View|RedirectResponse
    {
        $session = $this->getCurrentSession(request());

        if (!$session || !$session->is_completed) {
            return redirect()->route('test.form')
                ->with('error', 'No completed test session found.');
        }

        return view('public.test.completed', compact('session'));
    }

    /**
     * Show thank you page
     */
    public function thankYou(): View|RedirectResponse
    {
        $session = $this->getCurrentSession(request());
        if (!$session) {
            return redirect()->route('test.form')->with('error', 'No active session found.');
        }

        // boleh masuk jika sudah 'thanks' atau sudah 'completed'
        if (!in_array($session->current_step, ['thanks', 'completed']) && !$session->is_completed) {
            return redirect()->route('test.form')->with('error', 'Please complete the assessment first.');
        }

        return view('public.test.thank-you', compact('session'));
    }
    /**
     * Calculate progress percentage based on current step
     */
    private function calculateProgress(string $currentStep): float
    {
        $progressMap = [
            'form_data' => 0,
            'st30_stage1' => 15,    // ST-30: 60% total, 15% per stage
            'st30_stage2' => 30,
            'st30_stage3' => 45,
            'st30_stage4' => 60,
            'sjt_page1' => 68,      // SJT: 40% total, 8% per page
            'sjt_page2' => 76,
            'sjt_page3' => 84,
            'sjt_page4' => 92,
            'sjt_page5' => 100,
            'completed' => 100
        ];

        return $progressMap[$currentStep] ?? 0;
    }

    /**
     * Get questions excluding already selected from previous stages
     */
    private function getQuestionsExcludingStages(string $versionId, TestSession $session, array $excludeStages): Collection
    {
        $excludedIds = QuestionHelper::getSelectedQuestionIds($session, $excludeStages);

        return ST30Question::where('version_id', $versionId)
            ->where('is_active', true)
            ->whereNotIn('id', $excludedIds)
            ->get();
    }

    /**
     * Get current test session
     */
    private function getCurrentSession(Request $request): ?TestSession
    {
        $sessionToken = $request->session()->get('test_session_token');

        if (!$sessionToken) {
            return null;
        }

        return TestSession::where('session_token', $sessionToken)->first();
    }

    /**
     * Generate unique response ID
     */
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

    /**
     * Redirect to current step based on session state
     */
    private function redirectToCurrentStep(TestSession $session): RedirectResponse
    {
        // Store session token
        session(['test_session_token' => $session->session_token]);

        switch ($session->current_step) {
            case 'form_data':
            case 'st30_stage1':
                return redirect()->route('test.st30.stage', ['stage' => 1]);
            case 'st30_stage2':
                return redirect()->route('test.st30.stage', ['stage' => 2]);
            case 'st30_stage3':
                return redirect()->route('test.st30.stage', ['stage' => 3]);
            case 'st30_stage4':
                return redirect()->route('test.st30.stage', ['stage' => 4]);
            case 'sjt_page1':
                return redirect()->route('test.sjt.page', ['page' => 1]);
            case 'sjt_page2':
                return redirect()->route('test.sjt.page', ['page' => 2]);
            case 'sjt_page3':
                return redirect()->route('test.sjt.page', ['page' => 3]);
            case 'sjt_page4':
                return redirect()->route('test.sjt.page', ['page' => 4]);
            case 'sjt_page5':
                return redirect()->route('test.sjt.page', ['page' => 5]);
            case 'thanks':
                return redirect()->route('test.thank-you');
            case 'completed':
                return redirect()->route('test.completed');
            default:
                return redirect()->route('test.st30.stage', ['stage' => 1]);
        }
    }

    /**
     * Get current test session
     */
    private function getCurrentTestSession(): ?TestSession
    {
        $sessionToken = session('test_session_token');

        if (!$sessionToken) {
            return null;
        }

        return TestSession::where('session_token', $sessionToken)->first();
    }

    /**
     * Generate unique test session ID
     */
    private function generateTestSessionId(): string
    {
        do {
            $id = 'TS' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (TestSession::where('id', $id)->exists());

        return $id;
    }

    /**
     * Generate unique test result ID
     */
    private function generateTestResultId(): string
    {
        do {
            $id = 'TR' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (TestResult::where('id', $id)->exists());

        return $id;
    }
}
