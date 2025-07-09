<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\User;
use App\Models\Period;
use App\Models\Target;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function index()
    {
        $objectives = Objective::with(['user', 'period', 'target'])->get();
        return view('objectives.index', compact('objectives'));
    }

    
    // Show the form for creating a new objective
    public function create()
    {
        $users = User::all();
        $periods = Period::all();
        $targets = Target::all();
        return view('objectives.create', compact('users', 'periods', 'targets'));
    }

    // Store a newly created objective in storage
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
            'target_id' => 'required|exists:targets,id',
            'objective' => 'required|string'
            
        ]);

        Objective::create($request->all());

        return redirect()->route('objectives.index')->with('success', 'Objective created successfully.');
    }

    // Display the specified objective
    public function show(Objective $objective)
    {
        return view('objectives.show', compact('objective'));
    }

    // Show the form for editing the specified objective
    public function edit(Objective $objective)
    {
        $users = User::all();
        $periods = Period::all();
        $targets = Target::all();
        return view('objectives.edit', compact('objective', 'users', 'periods', 'targets'));
    }

    // Update the specified objective in storage
    public function update(Request $request, Objective $objective)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
            'target_id' => 'required|exists:targets,id',
            'objective' => 'required|string',
            'actions' => 'nullable|string',
            'half_year_comment' => 'nullable|string',
            'annual_comment' => 'nullable|string',
            'half_year_rating' => 'nullable|string',
            'annual_rating' => 'nullable|string',
        ]);

        $objective->update($request->all());

        return redirect()->route('objectives.index')->with('success', 'Objective updated successfully.');
    }

    // Remove the specified objective from storage
    public function destroy(Objective $objective)
    {
        $objective->delete();

        return redirect()->route('objectives.index')->with('success', 'Objective deleted successfully.');
    }
}
