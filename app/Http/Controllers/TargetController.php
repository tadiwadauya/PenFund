<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index()
    {
        $targets = Target::all();
        return view('targets.index', compact('targets'));
    }

    // Show the form for creating a new target
    public function create()
    {
        return view('targets.create');
    }

    // Store a newly created target in storage
    public function store(Request $request)
    {
        $request->validate([
            'target_name' => 'required|string|max:255|unique:targets,target_name',
        ]);

        Target::create($request->all());

        return redirect()->route('targets.index')->with('success', 'Target created successfully.');
    }

    // Display the specified target
    public function show(Target $target)
    {
        return view('targets.show', compact('target'));
    }

    // Show the form for editing the specified target
    public function edit(Target $target)
    {
        return view('targets.edit', compact('target'));
    }

    // Update the specified target in storage
    public function update(Request $request, Target $target)
    {
        $request->validate([
            'target_name' => 'required|string|max:255|unique:targets,target_name,' . $target->id,
        ]);

        $target->update($request->all());

        return redirect()->route('targets.index')->with('success', 'Target updated successfully.');
    }

    // Remove the specified target from storage
    public function destroy(Target $target)
    {
        $target->delete();

        return redirect()->route('targets.index')->with('success', 'Target deleted successfully.');
    }
}
