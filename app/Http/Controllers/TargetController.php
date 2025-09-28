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

    // Create the target and capture it in a variable
    $target = Target::create([
        'target_name' => $request->target_name,
        'period_id'   => $request->period_id,
        'user_id'     => auth()->id(), // always logged-in user
    ]);

    // Use the correct route parameter name
    return redirect()->route('user.performance.show', ['period' => $target->period_id])
                     ->with('success', 'Key Task created successfully.');
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

        $userId = $target->user_id; // Ensure this field exists in the initiatives table
        $periodId = $target->period_id;

        return redirect()->route('user.performance.show', ['period' => $periodId])->with('success', 'Key Task updated successfully.');
                        
    }

    public function updateInlineTarget(Request $request, $id)
    {
        $request->validate([
            'self_rating'  => 'nullable|integer|min:1|max:6',
            'self_comment' => 'nullable|string|max:1000',
        ]);
    
        $target = Target::findOrFail($id);
    
        $target->update([
            'self_rating'  => $request->input('self_rating'),
            'self_comment' => $request->input('self_comment'),
        ]);
    
        return redirect()->back()->with('success', 'Self-assessment updated successfully.');
    }
    

    
    


    /**
     * Remove the specified target from storage.
     */
    public function destroy(Target $target)
    {
        $target->delete();

        $userId = $target->user_id; // Ensure this field exists in the initiatives table
        $periodId = $target->period_id;

        return redirect()->route('user.performance.show', ['period' => $periodId])->with('success', 'Key Task deleted successfully.');
           
    }


}
