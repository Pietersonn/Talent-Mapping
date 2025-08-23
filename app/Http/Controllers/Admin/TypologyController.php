<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypologyDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypologyController extends Controller
{
    /**
     * Display a listing of typologies
     */
    public function index()
    {
        $typologies = TypologyDescription::orderBy('typology_code')->paginate(15);

        return view('admin.questions.typologies.index', compact('typologies'));
    }

    /**
     * Show the form for creating a new typology
     */
    public function create()
    {
        return view('admin.questions.typologies.create');
    }

    /**
     * Store a newly created typology
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'typology_code' => 'required|string|max:10|unique:typology_descriptions,typology_code',
            'typology_name' => 'required|string|max:255',
            'description' => 'required|string',
            'characteristics' => 'nullable|string',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'career_suggestions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            TypologyDescription::create([
                'typology_code' => strtoupper($request->typology_code),
                'typology_name' => $request->typology_name,
                'description' => $request->description,
                'characteristics' => $request->characteristics,
                'strengths' => $request->strengths,
                'weaknesses' => $request->weaknesses,
                'career_suggestions' => $request->career_suggestions,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('admin.typologies.index')
                ->with('success', 'Typology created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create typology: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified typology
     */
    public function show(TypologyDescription $typology)
    {
        return view('admin.questions.typologies.show', compact('typology'));
    }

    /**
     * Show the form for editing the specified typology
     */
    public function edit(TypologyDescription $typology)
    {
        return view('admin.questions.typologies.edit', compact('typology'));
    }

    /**
     * Update the specified typology
     */
    public function update(Request $request, TypologyDescription $typology)
    {
        $validator = Validator::make($request->all(), [
            'typology_code' => 'required|string|max:10|unique:typology_descriptions,typology_code,' . $typology->typology_code . ',typology_code',
            'typology_name' => 'required|string|max:255',
            'description' => 'required|string',
            'characteristics' => 'nullable|string',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'career_suggestions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $typology->update([
                'typology_code' => strtoupper($request->typology_code),
                'typology_name' => $request->typology_name,
                'description' => $request->description,
                'characteristics' => $request->characteristics,
                'strengths' => $request->strengths,
                'weaknesses' => $request->weaknesses,
                'career_suggestions' => $request->career_suggestions,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('admin.typologies.index')
                ->with('success', 'Typology updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update typology: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified typology
     */
    public function destroy(TypologyDescription $typology)
    {
        try {
            // Check if typology is being used in ST30 questions
            $questionsCount = $typology->st30Questions()->count();

            if ($questionsCount > 0) {
                return redirect()->back()
                    ->with('error', "Cannot delete typology. It is being used by {$questionsCount} ST-30 questions.");
            }

            $typology->delete();

            return redirect()->route('admin.typologies.index')
                ->with('success', 'Typology deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete typology: ' . $e->getMessage());
        }
    }
}
