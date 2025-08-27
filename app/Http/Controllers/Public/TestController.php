<?php

namespace App\Http\Controllers\Public;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Event;
use App\Models\SJTQuestion;
use App\Models\SJTResponse;
use App\Models\ST30Question;
use App\Models\ST30Response;
use App\Models\TestSession;
use App\Models\TestResult;
use App\Models\QuestionVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

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
            'started_at' => now(),
        ]);

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
     * Show ST-30 test stage
     */
    public function st30Stage($stage): View|RedirectResponse
    {
        if (!in_array($stage, [1, 2, 3, 4])) {
            abort(404);
        }

        $session = $this->getCurrentTestSession();
        if (!$session) {
            return redirect()->route('test.form')
                ->with('error', 'Test session not found. Please start a new test.');
        }

        // Allow navigation to current or previous stages
        $completedStages = ST30Response::where('session_id', $session->id)
            ->pluck('stage_number')
            ->sort()
            ->values()
            ->toArray();

        $maxAllowedStage = empty($completedStages) ? 1 : max($completedStages) + 1;

        // Allow access to completed stages (for back navigation) or next stage
        if ($stage > $maxAllowedStage) {
            return redirect()->route('test.st30.stage', ['stage' => $maxAllowedStage])
                ->with('error', 'Complete previous stages first.');
        }

        // Get available questions based on stage and previous responses
        $availableQuestions = $this->getAvailableQuestionsForStage($session, $stage);

        // Get previous selections for this stage (if any)
        $previousSelection = ST30Response::where('session_id', $session->id)
            ->where('stage_number', $stage)
            ->first();

        $selectedQuestions = $previousSelection ?
            json_decode($previousSelection->selected_items, true) ?? [] : [];

        return view('public.test.st30.stage', compact('stage', 'availableQuestions', 'session', 'selectedQuestions'));
    }

    /**
     * Store ST-30 stage answers
     */
    public function storeST30Stage(Request $request, $stage): RedirectResponse
    {
        $request->validate([
            'selected_questions' => 'required|array|min:5|max:7',
            'selected_questions.*' => 'exists:st30_questions,id'
        ]);

        $session = $this->getCurrentTestSession();
        if (!$session) {
            return redirect()->route('test.form')
                ->with('error', 'Test session not found.');
        }

        if (!in_array($stage, [1, 2, 3, 4])) {
            abort(404);
        }

        // Get active ST30 version
        $activeVersion = QuestionVersion::where('type', 'st30')
            ->where('is_active', true)
            ->first();

        if (!$activeVersion) {
            return redirect()->route('test.form')
                ->with('error', 'No active ST30 version found.');
        }

        // Check if response for this stage already exists (for back navigation)
        $existingResponse = ST30Response::where('session_id', $session->id)
            ->where('stage_number', $stage)
            ->first();

        if ($existingResponse) {
            // Update existing response
            $existingResponse->update([
                'selected_items' => json_encode($request->selected_questions),
                'for_scoring' => in_array($stage, [1, 3]), // Stage 1 & 3 for scoring
            ]);
        } else {
            // Create new response
            ST30Response::create([
                'id' => $this->generateResponseId('ST30'),
                'session_id' => $session->id,
                'question_version_id' => $activeVersion->id,
                'stage_number' => $stage,
                'selected_items' => json_encode($request->selected_questions),
                'for_scoring' => in_array($stage, [1, 3]), // Stage 1 & 3 for scoring
            ]);
        }

        // Update session current step only if moving forward
        $completedStages = ST30Response::where('session_id', $session->id)
            ->pluck('stage_number')
            ->sort()
            ->values()
            ->toArray();

        $maxCompletedStage = empty($completedStages) ? 0 : max($completedStages);

        if ($stage < 4) {
            // Update current step to next stage or keep current if it's higher
            $nextStage = $stage + 1;
            $currentStep = 'st30_stage' . $nextStage;

            // Only update if we're progressing forward
            if ($maxCompletedStage >= $stage) {
                $session->update(['current_step' => $currentStep]);
            }

            return redirect()->route('test.st30.stage', ['stage' => $nextStage])
                ->with('success', 'Stage ' . $stage . ' completed successfully!');
        } else {
            // ST-30 completed, move to SJT
            $session->update([
                'current_step' => 'sjt_page1'
            ]);

            return redirect()->route('test.sjt.page', ['page' => 1])
                ->with('success', 'ST-30 completed! Starting SJT assessment...');
        }
    }

    /**
     * Show SJT test page
     */
    public function sjtPage($page): View|RedirectResponse
    {
        if (!in_array($page, [1, 2, 3, 4, 5])) {
            abort(404);
        }

        $session = $this->getCurrentTestSession();
        if (!$session) {
            return redirect()->route('test.form')
                ->with('error', 'Test session not found.');
        }

        // Check if user should be in this page
        $expectedStep = 'sjt_page' . $page;
        if ($session->current_step !== $expectedStep && $page != 1) {
            return $this->redirectToCurrentStep($session);
        }

        // Get active SJT version
        $activeVersion = QuestionVersion::where('type', 'sjt')
            ->where('is_active', true)
            ->first();

        if (!$activeVersion) {
            return redirect()->route('test.form')
                ->with('error', 'No active SJT version found.');
        }

        // Get SJT questions for this page (10 questions per page)
        $startNumber = (($page - 1) * 10) + 1;
        $endNumber = $page * 10;

        $questions = SJTQuestion::where('version_id', $activeVersion->id)
            ->whereBetween('number', [$startNumber, $endNumber])
            ->with(['options' => function($query) {
                $query->orderBy('option_letter');
            }])
            ->orderBy('number')
            ->get();

        return view('public.test.sjt.page', compact('page', 'questions', 'session'));
    }

    /**
     * Store SJT page answers
     */
    public function storeSJTPage(Request $request, $page): RedirectResponse
    {
        $session = $this->getCurrentTestSession();
        if (!$session) {
            return redirect()->route('test.form')
                ->with('error', 'Test session not found.');
        }

        // Validate responses (should have 10 responses for 10 questions)
        $request->validate([
            'responses' => 'required|array|size:10',
            'responses.*' => 'required|exists:sjt_options,id'
        ]);

        // Get active SJT version
        $activeVersion = QuestionVersion::where('type', 'sjt')
            ->where('is_active', true)
            ->first();

        if (!$activeVersion) {
            return redirect()->route('test.form')
                ->with('error', 'No active SJT version found.');
        }

        // Store each SJT response
        foreach ($request->responses as $questionId => $optionId) {
            SJTResponse::create([
                'id' => $this->generateResponseId('SJT'),
                'session_id' => $session->id,
                'question_version_id' => $activeVersion->id,
                'question_id' => $questionId,
                'selected_option_id' => $optionId,
                'page_number' => $page,
            ]);
        }

        // Update session progress
        if ($page < 5) {
            $session->update([
                'current_step' => 'sjt_page' . ($page + 1)
            ]);

            return redirect()->route('test.sjt.page', ['page' => $page + 1])
                ->with('success', 'Page ' . $page . ' completed successfully!');
        } else {
            // All SJT completed - Generate results
            $session->update([
                'current_step' => 'completed',
                'is_completed' => true,
                'completed_at' => now()
            ]);

            // Create TestResult record
            $testResult = TestResult::create([
                'id' => $this->generateTestResultId(),
                'session_id' => $session->id,
                'st30_results' => null, // Will be populated by event listener
                'sjt_results' => null,  // Will be populated by event listener
                'dominant_typology' => null,
            ]);

            // Trigger result calculation event
            event(new \App\Events\TestCompleted($session, $testResult));

            return redirect()->route('test.thank-you')
                ->with('success', 'Test completed successfully! Your results are being processed.');
        }
    }

    /**
     * Show thank you page
     */
    public function thankYou(): View|RedirectResponse
    {
        $session = $this->getCurrentTestSession();

        if (!$session || !$session->is_completed) {
            return redirect()->route('test.form')
                ->with('error', 'No completed test session found.');
        }

        return view('public.test.thank-you', compact('session'));
    }

    /**
     * Complete test and generate results
     */
    public function complete(Request $request): RedirectResponse
    {
        $session = $this->getCurrentTestSession();

        if (!$session || !$session->is_completed) {
            return redirect()->route('test.form')
                ->with('error', 'Test session not found or not completed.');
        }

        // TODO: Implement result calculation and email sending
        // event(new TestCompleted($session));

        return redirect()->route('home')
            ->with('success', 'Test completed successfully! Results will be sent to your email.');
    }

    // ===== HELPER METHODS =====

    /**
     * Get current active test session for authenticated user
     */
    private function getCurrentTestSession(): ?TestSession
    {
        return TestSession::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->latest()
            ->first();
    }

    /**
     * Redirect user to their current step based on session state
     */
    private function redirectToCurrentStep(TestSession $session): RedirectResponse
    {
        switch ($session->current_step) {
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
            case 'completed':
                return redirect()->route('test.thank-you');
            default:
                return redirect()->route('test.st30.stage', ['stage' => 1]);
        }
    }

    /**
     * Get available questions for ST-30 stage based on previous responses (FIXED)
     */
    private function getAvailableQuestionsForStage(TestSession $session, int $stage): \Illuminate\Database\Eloquent\Collection
    {
        // Get active ST30 version
        $activeVersion = QuestionVersion::where('type', 'st30')
            ->where('is_active', true)
            ->first();

        if (!$activeVersion) {
            return collect();
        }

        $allQuestions = ST30Question::where('version_id', $activeVersion->id)
            ->where('is_active', true)
            ->orderBy('number')
            ->get();

        switch ($stage) {
            case 1:
                // Stage 1: All 30 questions available
                return $allQuestions;

            case 2:
                // Stage 2: Questions NOT selected in Stage 1
                $stage1Response = ST30Response::where('session_id', $session->id)
                    ->where('stage_number', 1)
                    ->first();

                $excludedIds = $stage1Response ?
                    json_decode($stage1Response->selected_items, true) ?? [] : [];

                return $allQuestions->whereNotIn('id', $excludedIds);

            case 3:
                // Stage 3: Questions NOT selected in Stage 1 AND Stage 2
                $stage1Response = ST30Response::where('session_id', $session->id)
                    ->where('stage_number', 1)->first();
                $stage2Response = ST30Response::where('session_id', $session->id)
                    ->where('stage_number', 2)->first();

                $excludedIds = [];
                if ($stage1Response) {
                    $excludedIds = array_merge($excludedIds, json_decode($stage1Response->selected_items, true) ?? []);
                }
                if ($stage2Response) {
                    $excludedIds = array_merge($excludedIds, json_decode($stage2Response->selected_items, true) ?? []);
                }

                return $allQuestions->whereNotIn('id', $excludedIds);

            case 4:
                // Stage 4: Questions NOT selected in Stage 1, 2, AND 3
                $responses = ST30Response::where('session_id', $session->id)
                    ->whereIn('stage_number', [1, 2, 3])
                    ->get();

                $excludedIds = [];
                foreach ($responses as $response) {
                    $selectedIds = json_decode($response->selected_items, true) ?? [];
                    $excludedIds = array_merge($excludedIds, $selectedIds);
                }

                return $allQuestions->whereNotIn('id', $excludedIds);

            default:
                return collect();
        }
    }

    /**
     * Generate unique test session ID
     */
    private function generateTestSessionId(): string
    {
        $prefix = 'TS';

        $lastId = DB::table('test_sessions')
            ->where('id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('id');

        if (!$lastId) {
            return $prefix . '001';
        }

        $lastNumber = (int) substr($lastId, strlen($prefix));
        $newNumber = $lastNumber + 1;

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique response ID
     */
    private function generateResponseId(string $type): string
    {
        $prefix = $type === 'ST30' ? 'STR' : 'SJR';  // 3 karakter
        $table = $type === 'ST30' ? 'st30_responses' : 'sjt_responses';

        $lastId = DB::table($table)
            ->where('id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->value('id');

        if (!$lastId) {
            return $prefix . '01';  // STR01 = 5 karakter total
        }

        $lastNumber = (int) substr($lastId, strlen($prefix));
        $newNumber = $lastNumber + 1;

        return $prefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);  // 2 digit = STR99 max
    }
}
