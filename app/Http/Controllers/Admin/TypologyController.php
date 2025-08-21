<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypologyDescription;
use App\Models\ST30Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypologyController extends Controller
{
    public function index(Request $request)
    {
        $query = TypologyDescription::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('typology_name', 'like', "%{$search}%")
                  ->orWhere('typology_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $typologies = $query->withCount(['st30Questions as questions_count'])
                           ->orderBy('typology_code')
                           ->paginate(15);

        // Statistics
        $totalTypologies = TypologyDescription::count();
        $activeTypologies = $totalTypologies; // All considered active
        $totalQuestions = ST30Question::count();
        $typologyGroups = 8; // Static count for 8 groups (H, N, S, Gi, T, R, E, Te)

        // Group statistics - based on typology code patterns
        $groupStats = [
            'H' => ['name' => 'Headman', 'count' => 0, 'questions' => 0],
            'N' => ['name' => 'Networking', 'count' => 0, 'questions' => 0],
            'S' => ['name' => 'Servicing', 'count' => 0, 'questions' => 0],
            'Gi' => ['name' => 'Generating Ideas', 'count' => 0, 'questions' => 0],
            'T' => ['name' => 'Thinking', 'count' => 0, 'questions' => 0],
            'R' => ['name' => 'Reasoning', 'count' => 0, 'questions' => 0],
            'E' => ['name' => 'Elementary', 'count' => 0, 'questions' => 0],
            'Te' => ['name' => 'Technical', 'count' => 0, 'questions' => 0],
        ];

        // Calculate group stats based on typology code patterns
        foreach ($typologies as $typology) {
            $code = $typology->typology_code;
            $group = $this->getTypologyGroup($code);
            if (isset($groupStats[$group])) {
                $groupStats[$group]['count']++;
                $groupStats[$group]['questions'] += $typology->questions_count ?? 0;
            }
        }

        return view('admin.questions.typologies.index', compact(
            'typologies',
            'totalTypologies',
            'activeTypologies',
            'totalQuestions',
            'typologyGroups',
            'groupStats'
        ));
    }

    public function show(TypologyDescription $typology)
    {
        // Load related questions
        $questions = ST30Question::where('typology_code', $typology->typology_code)
                                ->with(['questionVersion'])
                                ->orderBy('number')
                                ->get();

        // Get typology group from code
        $typologyGroup = $this->getTypologyGroup($typology->typology_code);

        // Related typologies (same group pattern)
        $relatedTypologies = TypologyDescription::whereRaw('SUBSTRING(typology_code, 1, 1) = ?', [$typologyGroup])
                                               ->where('id', '!=', $typology->id)
                                               ->limit(5)
                                               ->get();

        // Usage statistics (mock data)
        $usageStats = collect();
        $totalResponses = 0;
        $avgSelectionRate = 0;

        // Group descriptions
        $groupDescriptions = [
            'H' => 'Headman - Activities that interact with others to control, influence, or supervise',
            'N' => 'Networking - Activities with others for cooperation, mentoring, coaching, and representing',
            'S' => 'Servicing - Activities that interact with others in caring, healing, or helping',
            'G' => 'Generating Ideas - Individual activities related to intuition, ideas, and creativity',
            'T' => 'Thinking - Individual activities using logical thinking, facts, or analysis',
            'R' => 'Reasoning - Individual activities using logic to find or prove something',
            'E' => 'Elementary - Individual activities that require physical energy, precision, and spatial awareness',
        ];

        return view('admin.questions.typologies.show', compact(
            'typology',
            'questions',
            'relatedTypologies',
            'usageStats',
            'totalResponses',
            'avgSelectionRate',
            'groupDescriptions'
        ));
    }

    public function update(Request $request, TypologyDescription $typology)
    {
        $validated = $request->validate([
            'typology_name' => 'required|string|max:255',
            'description' => 'nullable|string'
            // Only validate columns that exist
        ]);

        $typology->update($validated);

        return redirect()->route('admin.questions.typologies.show', $typology)
                        ->with('success', 'Typology updated successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'typology_code' => 'required|string|max:10|unique:typology_descriptions',
            'typology_name' => 'required|string|max:255',
            'description' => 'nullable|string'
            // Only validate columns that exist
        ]);

        $typology = TypologyDescription::create($validated);

        return redirect()->route('admin.questions.typologies.index')
                        ->with('success', 'Typology created successfully.');
    }

    public function destroy(TypologyDescription $typology)
    {
        $questionsCount = ST30Question::where('typology_code', $typology->typology_code)->count();

        if ($questionsCount > 0) {
            return redirect()->back()
                           ->with('error', 'Cannot delete typology that is used in questions.');
        }

        $typology->delete();

        return redirect()->route('admin.questions.typologies.index')
                        ->with('success', 'Typology deleted successfully.');
    }

    /**
     * Determine typology group from code pattern
     */
    private function getTypologyGroup($code)
    {
        // Based on typical ST-30 patterns
        if (str_starts_with($code, 'AMB') || str_starts_with($code, 'CMD') || str_starts_with($code, 'COM')) {
            return 'H'; // Headman
        }
        if (str_starts_with($code, 'MOT') || str_starts_with($code, 'ARR')) {
            return 'N'; // Networking
        }
        if (str_starts_with($code, 'CAR') || str_starts_with($code, 'SER')) {
            return 'S'; // Servicing
        }
        if (str_starts_with($code, 'CRE') || str_starts_with($code, 'DES')) {
            return 'G'; // Generating Ideas
        }
        if (str_starts_with($code, 'ANA') || str_starts_with($code, 'TRE')) {
            return 'T'; // Thinking
        }
        if (str_starts_with($code, 'EVA') || str_starts_with($code, 'RES')) {
            return 'R'; // Reasoning
        }
        if (str_starts_with($code, 'EDU') || str_starts_with($code, 'EXP')) {
            return 'E'; // Elementary
        }

        return substr($code, 0, 1); // Default to first character
    }
}
