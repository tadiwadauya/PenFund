<?php

namespace App\Http\Controllers;

use App\Models\EvaluationSection;
use Illuminate\Http\Request;

class EvaluationSectionController extends Controller
{
    // List all sections
    public function index() {
        $sections = EvaluationSection::all();
        return view('evaluation_sections.index', compact('sections'));
    }

    // Show form to create section
    public function create() {
        return view('evaluation_sections.create');
    }

    // Store a new section
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        EvaluationSection::create([
            'name' => $request->name
        ]);

        return redirect()->route('evaluation_sections.index')
                         ->with('success', 'Section created successfully!');
    }

    private function gradeFromNumber($num)
    {
        if ($num >= 5.5) return 'A1';
        if ($num >= 4.5) return 'A2';
        if ($num >= 3.5) return 'B1';
        if ($num >= 2.5) return 'B2';
        if ($num >= 1.5) return 'C1';
        if ($num >= 0.5) return 'C2';
        return '-';
    }
}
