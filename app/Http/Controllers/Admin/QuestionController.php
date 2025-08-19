<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionVersion;
use App\Models\ST30Question;
use App\Models\SJTQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Display a listing of question versions
     */
    public function index()
    {
        $st30Versions = QuestionVersion::where('type', 'st30')
            ->withCount(['st30Questions'])
            ->orderBy('version', 'desc')
            ->get();

        $sjtVersions = QuestionVersion::where('type', 'sjt')
            ->withCount(['sjtQuestions'])
            ->orderBy('version', 'desc')
            ->get();

        $activeVersions = [
            'st30' => QuestionVersion::getActive('st30'),
            'sjt' => QuestionVersion::getActive('sjt')
        ];

        return view('admin.questions.index', compact('st30Versions', 'sjtVersions', 'activeVersions'));
    }

    /**
     * Show the form for creating a new version
     */
    public function create()
    {
        return view('admin.questions.create');
    }

    /**
     * Store a newly created version
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:st30,sjt',
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        // Get next version number
        $latestVersion = QuestionVersion::where('type', $request->type)
            ->orderBy('version', 'desc')
            ->first();

        $nextVersion = $latestVersion ? $latestVersion->version + 1 : 1;

        $questionVersion = QuestionVersion::create([
            'version' => $nextVersion,
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => false, // New versions start as inactive
        ]);

        // TODO: Add activity logging later
        // Log::info('Question version created', [
        //     'version_id' => $questionVersion->id,
        //     'user_id' => Auth::id(),
        //     'action' => 'created'
        // ]);

        return redirect()
            ->route('admin.questions.show', $questionVersion)
            ->with('success', 'Question version created successfully.');
    }

    /**
     * Display the specified version
     */
    public function show(QuestionVersion $questionVersion)
    {
        $questionVersion->load(['st30Questions', 'sjtQuestions']);

        $statistics = [
            'total_questions' => $questionVersion->questions_count,
            'st30_count' => $questionVersion->st30Questions->count(),
            'sjt_count' => $questionVersion->sjtQuestions->count(),
            'usage_stats' => $questionVersion->usage_stats,
        ];

        if ($questionVersion->type === 'st30') {
            $typologyStats = $questionVersion->st30Questions
                ->groupBy('typology_code')
                ->map(fn($questions) => $questions->count())
                ->toArray();
        } else {
            $competencyStats = $questionVersion->sjtQuestions
                ->groupBy('competency')
                ->map(fn($questions) => $questions->count())
                ->toArray();
        }

        return view('admin.questions.show', compact(
            'questionVersion',
            'statistics',
            'typologyStats',
            'competencyStats'
        ));
    }

    /**
     * Show the form for editing the specified version
     */
    public function edit(QuestionVersion $questionVersion)
    {
        return view('admin.questions.edit', compact('questionVersion'));
    }

    /**
     * Update the specified version
     */
    public function update(Request $request, QuestionVersion $questionVersion)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $questionVersion->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // TODO: Add activity logging later
        // Log::info('Question version updated', [
        //     'version_id' => $questionVersion->id,
        //     'user_id' => Auth::id(),
        //     'action' => 'updated'
        // ]);

        return redirect()
            ->route('admin.questions.show', $questionVersion)
            ->with('success', 'Question version updated successfully.');
    }

    /**
     * Remove the specified version
     */
    public function destroy(QuestionVersion $questionVersion)
    {
        // Check if version has responses
        if ($questionVersion->hasResponses()) {
            return redirect()
                ->route('admin.questions.index')
                ->with('error', 'Cannot delete version that has been used in tests.');
        }

        // Check if it's the active version
        if ($questionVersion->is_active) {
            return redirect()
                ->route('admin.questions.index')
                ->with('error', 'Cannot delete the active version.');
        }

        $type = $questionVersion->type;
        $name = $questionVersion->name;

        $questionVersion->delete();

        // TODO: Add activity logging later
        // Log::info('Question version deleted', [
        //     'version_name' => $name,
        //     'version_type' => $type,
        //     'user_id' => Auth::id(),
        //     'action' => 'deleted'
        // ]);

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Question version deleted successfully.');
    }

    /**
     * Activate the specified version
     */
    public function activate(QuestionVersion $questionVersion)
    {
        // Check if version has questions
        if ($questionVersion->questions_count === 0) {
            return redirect()
                ->route('admin.questions.show', $questionVersion)
                ->with('error', 'Cannot activate version with no questions.');
        }

        // For SJT versions, check if all questions have complete options
        if ($questionVersion->type === 'sjt') {
            $incompleteQuestions = $questionVersion->sjtQuestions()
                ->whereDoesntHave('options', function ($query) {
                    $query->whereIn('option_letter', ['a', 'b', 'c', 'd', 'e'])
                          ->havingRaw('COUNT(DISTINCT option_letter) = 5');
                })
                ->count();

            if ($incompleteQuestions > 0) {
                return redirect()
                    ->route('admin.questions.show', $questionVersion)
                    ->with('error', "Cannot activate version. {$incompleteQuestions} questions have incomplete options.");
            }
        }

        $questionVersion->activate();

        // TODO: Add activity logging later
        // Log::info('Question version activated', [
        //     'version_id' => $questionVersion->id,
        //     'user_id' => Auth::id(),
        //     'action' => 'activated'
        // ]);

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Question version activated successfully.');
    }

    /**
     * Clone version for editing
     */
    public function clone(QuestionVersion $questionVersion)
    {
        // Get next version number
        $latestVersion = QuestionVersion::where('type', $questionVersion->type)
            ->orderBy('version', 'desc')
            ->first();

        $nextVersion = $latestVersion->version + 1;

        // Create new version
        $newVersion = QuestionVersion::create([
            'version' => $nextVersion,
            'type' => $questionVersion->type,
            'name' => $questionVersion->name . ' (Copy)',
            'description' => 'Cloned from ' . $questionVersion->name,
            'is_active' => false,
        ]);

        // Clone questions
        if ($questionVersion->type === 'st30') {
            foreach ($questionVersion->st30Questions as $question) {
                ST30Question::create([
                    'version_id' => $newVersion->id,
                    'number' => $question->number,
                    'statement' => $question->statement,
                    'typology_code' => $question->typology_code,
                    'is_active' => $question->is_active,
                ]);
            }
        } else {
            foreach ($questionVersion->sjtQuestions as $question) {
                $newQuestion = SJTQuestion::create([
                    'version_id' => $newVersion->id,
                    'number' => $question->number,
                    'question_text' => $question->question_text,
                    'competency' => $question->competency,
                    'page_number' => $question->page_number,
                    'is_active' => $question->is_active,
                ]);

                // Clone options
                foreach ($question->options as $option) {
                    $newQuestion->options()->create([
                        'option_letter' => $option->option_letter,
                        'option_text' => $option->option_text,
                        'score' => $option->score,
                        'competency_target' => $option->competency_target,
                    ]);
                }
            }
        }

        // TODO: Add activity logging later
        // Log::info('Question version cloned', [
        //     'new_version_id' => $newVersion->id,
        //     'source_version_id' => $questionVersion->id,
        //     'user_id' => Auth::id(),
        //     'action' => 'cloned'
        // ]);

        return redirect()
            ->route('admin.questions.show', $newVersion)
            ->with('success', 'Question version cloned successfully.');
    }

    /**
     * Get version statistics for AJAX
     */
    public function statistics(QuestionVersion $questionVersion)
    {
        $stats = [
            'questions_count' => $questionVersion->questions_count,
            'usage_stats' => $questionVersion->usage_stats,
            'is_active' => $questionVersion->is_active,
        ];

        if ($questionVersion->type === 'st30') {
            $stats['typology_distribution'] = $questionVersion->st30Questions
                ->groupBy('typology_code')
                ->map(fn($questions) => $questions->count());
        } else {
            $stats['competency_distribution'] = $questionVersion->sjtQuestions
                ->groupBy('competency')
                ->map(fn($questions) => $questions->count());

            $stats['incomplete_questions'] = $questionVersion->sjtQuestions()
                ->whereDoesntHave('options', function ($query) {
                    $query->whereIn('option_letter', ['a', 'b', 'c', 'd', 'e'])
                          ->havingRaw('COUNT(DISTINCT option_letter) = 5');
                })
                ->count();
        }

        return response()->json($stats);
    }
}
