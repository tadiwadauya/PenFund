<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\User;
use App\Models\Period;
use App\Models\Target;
use App\Models\Initiative;
use Illuminate\Http\Request;

class InitiativeController extends Controller
{
    public function index()
    {
        $initiatives = Initiative::with(['user', 'period', 'target','objective'])->get();
        return view('initiatives.index', compact('initiatives'));
    }

    
    // Show the form for creating a new Initiative
    public function create()
    {
        $users = User::all();
        $periods = Period::all();
        $targets = Target::all();
        $objectives = Objective::all();
        return view('initiatives.create', compact('users', 'periods', 'targets','objectives'));
    }

    // Store a newly created initiative in storage
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
            'target_id' => 'required|exists:targets,id',
            'initiative' => 'required|string',
            'objective_id'=>'required|string',
            
        ]);

        Initiative::create($request->all());

        return redirect()->route('initiatives.index')->with('success', 'initiative created successfully.');
    }

    // Display the specified Initiative
    public function show(Initiative $initiative)
    {
        return view('initiatives.show', compact('initiative'));
    }

    // Show the form for editing the specified Initiative
    public function edit(Initiative $initiative)
    {
        $users = User::all();
        $periods = Period::all();
        $targets = Target::all();
        $objectives = Objective::all();
        return view('initiatives.edit', compact('initiative', 'users', 'periods', 'targets','objectives'));
    }

    // Update the specified Initiative in storage
    public function update(Request $request, Initiative $initiative)
    {
        $request->validate([
           
            'period_id' => 'required|exists:periods,id',
            'target_id' => 'required|exists:targets,id',
            'objective_id' => 'required|string',
        ]);

        $initiative->update($request->all());

        return redirect()->route('initiatives.myinitiative')->with('success', 'Initiative updated successfully.');
    }

    // Remove the specified Initiative from storage
    public function destroy(Initiative $initiative)
    {
        $initiative->delete();

        return redirect()->route('initiatives.index')->with('success', 'Initiative deleted successfully.');
    }
}
