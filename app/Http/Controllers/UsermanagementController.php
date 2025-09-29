<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\EvaluationSection;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // List all tasks with section name
    public function index() {
        $tasks = Task::with('section')->get();
        return view('tasks.index', compact('tasks'));
    }

    // Show form to create task
    public function create() {
        $sections = EvaluationSection::all(); // dropdown to select section
        return view('tasks.create', compact('sections'));
    }

    // Store a new task
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'section_id' => 'required|exists:evaluation_sections,id'
        ]);

        Task::create([
            'name' => $request->name,
            'section_id' => $request->section_id
        ]);

        return redirect()->route('tasks.index')
                         ->with('success', 'Task created successfully!');
    }
}
