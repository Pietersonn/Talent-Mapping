<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetencyDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetencyController extends Controller
{
    /**
     * Display a listing of competencies
     */
    public function index()
    {
        $competencies = CompetencyDescription::withCount('sjtQuestions')
            ->orderBy('competency_name')
            ->get();

        return view('admin.questions.competencies.index', compact('competencies'));
    }

    /**
     * Show the form for editing competency
     */
    public function edit(CompetencyDescription $competencyDescription)
    {
        return view('admin.questions.competencies.edit', compact('competencyDescription'));
    }

    /**
     * Update the specified competency
     */
    public function update(Request $request, CompetencyDescription $competencyDescription)
    {
        $request->validate([
            'competency_name' => 'required|string|max:100',
            'strength_description' => 'required|string|max:1000',
            'weakness_description' => 'required|string|max:1000',
            'improvement_activity' => 'required|string|max:1000',
        ]);

        $competencyDescription->update($request->only([
            'competency_name',
            'strength_description',
            'weakness_description',
            'improvement_activity'
        ]));

        return redirect()->route('admin.competencies.index')
            ->with('success', 'Competency description updated successfully.');
    }

    /**
     * Display the specified competency
     */
    public function show(CompetencyDescription $competencyDescription)
    {
        $competencyDescription->load('sjtQuestions.questionVersion');

        return view('admin.questions.competencies.show', compact('competencyDescription'));
    }
}
