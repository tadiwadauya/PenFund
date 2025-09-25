<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\User;
use App\Models\Period;
use Illuminate\Http\Request;

class TargetController extends Controller
{


    public function index(Request $request)
    {
        $targetsQuery = Target::with(['user', 'period']); // eager load relations

        // Optional filter by user
        if ($request->filled('selected_user')) {
            $targetsQuery->where('user_id', $request->selected_user);
        }

        $targets = $targetsQuery->get();
        $managedUsers = User::all(); // for dropdown filter

        return view('targets.index', compact('targets', 'managedUsers'));
    }

    /**
     * Show the form for creating a new target.
     */
    public function create()
    {
        $periods = Period::all();
        return view('targets.create', compact('periods'));
    }

    /**
     * Store a newly created target in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_name' => 'required|string|max:255|unique:targets,target_name',
            'period_id'   => 'required|exists:periods,id',
        ]);

        Target::create([
            'target_name' => $request->target_name,
            'period_id'   => $request->period_id,
            'user_id'     => auth()->id(), // always logged-in user
        ]);

        return redirect()->route('targets.index')
                         ->with('success', 'Target created successfully.');
    }

    /**
     * Display the specified target.
     */
    public function show(Target $target)
    {
        $target->load(['user', 'period']); // eager load relations
        return view('targets.show', compact('target'));
    }

    /**
     * Show the form for editing the specified target.
     */
    public function edit(Target $target)
    {
        $periods = Period::all();
        return view('targets.edit', compact('target', 'periods'));
    }

    /**
     * Update the specified target in storage.
     */
    public function update(Request $request, Target $target)
    {
        $request->validate([
            'target_name' => 'required|string|max:255|unique:targets,target_name,' . $target->id,
            'period_id'   => 'required|exists:periods,id',
        ]);

        $target->update([
            'target_name' => $request->target_name,
            'period_id'   => $request->period_id,
            'user_id'     => auth()->id(), // override user_id
        ]);

        return redirect()->route('user.performance.index')
                         ->with('success', 'Key Task updated successfully.');
    }

    /**
     * Remove the specified target from storage.
     */
    public function destroy(Target $target)
    {
        $target->delete();

        return redirect()->route('targets.index')
                         ->with('success', 'Target deleted successfully.');
    }
}
