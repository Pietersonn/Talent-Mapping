<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ST30Question;
use App\Models\QuestionVersion;
use App\Models\TypologyDescription;
use Illuminate\Http\Request;

class ST30QuestionController extends Controller
{
    /**
     * Display a listing of ST-30 questions
     */
    public function index(Request $request)
    {
        $activeVersion = QuestionVersion::getActive('st30');

        // pilih versi dari request atau default active
        $selectedVersion = $request->filled('version')
            ? QuestionVersion::find($request->version)
            : $activeVersion;

        // semua versi ST-30 untuk dropdown
        $versions = QuestionVersion::where('type', 'st30')
            ->orderBy('version', 'desc')
            ->get();

        $questions = collect();
        if ($selectedVersion) {
            $questions = ST30Question::where('version_id', $selectedVersion->id)
                ->with(['questionVersion', 'typologyDescription'])
                ->orderBy('number')
                ->get();
        }

        // statistik distribusi tipologi
        $typologyStats = $questions->groupBy('typology_code')
            ->map(fn($items) => $items->count())
            ->toArray();

        $typologies = TypologyDescription::orderBy('typology_code')->get();

        return view('admin.questions.st30.index', compact(
            'questions',
            'selectedVersion',
            'activeVersion',
            'versions',
            'typologyStats',
            'typologies'
        ));
    }

    /**
     * Show the form for creating a new ST-30 question
     */
    public function create(Request $request)
    {
        $selectedVersion = $request->filled('version')
            ? QuestionVersion::find($request->version)
            : QuestionVersion::getActive('st30');

        if (!$selectedVersion) {
            return redirect()->route('admin.questions.index')
                ->with('error', 'No ST-30 version available. Please create a version first.');
        }

        // Next number
        $nextNumber = (int) ST30Question::where('version_id', $selectedVersion->id)->max('number') + 1;
        if ($nextNumber > 30) {
            return redirect()->route('admin.questions.st30.index', ['version' => $selectedVersion->id])
                ->with('error', 'This version already has 30 questions (maximum).');
        }

        $typologies = TypologyDescription::orderBy('typology_name')->get();
        $versions   = QuestionVersion::where('type', 'st30')->orderBy('version', 'desc')->get();

        return view('admin.questions.st30.create', compact(
            'selectedVersion',
            'nextNumber',
            'typologies',
            'versions'
        ));
    }

    /**
     * Store a newly created ST-30 question
     */
    public function store(Request $request)
    {
        $request->validate([
            'version_id'    => 'required|exists:question_versions,id',
            'number'        => 'required|integer|min:1|max:30',
            'statement'     => 'required|string|max:500',
            'typology_code' => 'required|exists:typology_descriptions,typology_code',
        ]);

        $exists = ST30Question::where('version_id', $request->version_id)
            ->where('number', $request->number)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'number' => 'Question number already exists in this version.'
            ])->withInput();
        }

        ST30Question::create([
            'version_id'    => $request->version_id,
            'number'        => $request->number,
            'statement'     => $request->statement,
            'typology_code' => $request->typology_code,
        ]);

        return redirect()->route('admin.questions.st30.index', ['version' => $request->version_id])
            ->with('success', 'ST-30 question created successfully.');
        }

    /**
     * Display the specified ST-30 question
     */
    public function show(ST30Question $st30Question)
    {
        $st30Question->load(['questionVersion', 'typologyDescription']);
        return view('admin.questions.st30.show', compact('st30Question'));
    }

    /**
     * Show the form for editing the specified ST-30 question
     */
    public function edit(ST30Question $st30Question)
    {
        $st30Question->load(['questionVersion']);
        $typologies = TypologyDescription::orderBy('typology_name')->get();
        $versions   = QuestionVersion::where('type', 'st30')->orderBy('version', 'desc')->get();

        return view('admin.questions.st30.edit', compact('st30Question', 'typologies', 'versions'));
    }

    /**
     * Update the specified ST-30 question
     */
    public function update(Request $request, ST30Question $st30Question)
    {
        $request->validate([
            'number'        => 'required|integer|min:1|max:30',
            'statement'     => 'required|string|max:500',
            'typology_code' => 'required|exists:typology_descriptions,typology_code',
        ]);

        $exists = ST30Question::where('version_id', $st30Question->version_id)
            ->where('number', $request->number)
            ->where('id', '!=', $st30Question->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'number' => 'Question number already exists in this version.'
            ])->withInput();
        }

        $st30Question->update([
            'number'        => $request->number,
            'statement'     => $request->statement,
            'typology_code' => $request->typology_code,
        ]);

        return redirect()->route('admin.questions.st30.index', ['version' => $st30Question->version_id])
            ->with('success', 'ST-30 question updated successfully.');
    }

    /**
     * Remove the specified ST-30 question
     */
    public function destroy(ST30Question $st30Question)
    {
        if (method_exists($st30Question, 'hasResponses') && $st30Question->hasResponses()) {
            return redirect()->route('admin.questions.st30.index', ['version' => $st30Question->version_id])
                ->with('error', 'Cannot delete question that has been used in tests.');
        }

        $versionId = $st30Question->version_id;
        $st30Question->delete();

        return redirect()->route('admin.questions.st30.index', ['version' => $versionId])
            ->with('success', 'ST-30 question deleted successfully.');
    }

    /**
     * Bulk import ST-30 questions
     */
    public function import(Request $request)
    {
        $request->validate([
            'version_id'  => 'required|exists:question_versions,id',
            'import_file' => 'required|file|mimes:csv,xlsx'
        ]);

        // TODO: Implement CSV/Excel import functionality

        return redirect()->route('admin.questions.st30.index', ['version' => $request->version_id])
            ->with('info', 'Import functionality will be implemented soon.');
    }

    /**
     * Bulk export ST-30 questions
     */
    public function export(Request $request)
    {
        $versionId = $request->get('version');
        if (!$versionId) {
            return redirect()->route('admin.questions.st30.index')
                ->with('error', 'Please select a version to export.');
        }

        // TODO: Implement export functionality

        return redirect()->route('admin.questions.st30.index', ['version' => $versionId])
            ->with('info', 'Export functionality will be implemented soon.');
    }

    /**
     * Reorder questions
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'version_id'         => 'required|exists:question_versions,id',
            'questions'          => 'required|array',
            'questions.*.id'     => 'required|exists:st30_questions,id',
            'questions.*.number' => 'required|integer|min:1|max:30',
        ]);

        foreach ($request->questions as $q) {
            ST30Question::where('id', $q['id'])->update(['number' => $q['number']]);
        }

        return response()->json(['success' => true]);
    }
}
