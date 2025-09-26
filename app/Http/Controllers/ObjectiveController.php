<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\User;
use App\Models\Period;
use App\Models\Target;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();

    // Check if the user is a departmental manager
    $department = Department::where('manager', $user->id)->first();
    // Check if the user is a section manager
    $section = Section::where('manager', $user->id)->first();

    // If not a manager at all
    if (!$department && !$section) {
        abort(403, 'You are not authorized to view this page.');
    }

    // Build the list of users this manager can manage
    $managedUsersQuery = User::query();
    if ($department) {
        $managedUsersQuery->orWhere('department', $department->department);
    }
    if ($section) {
        $managedUsersQuery->orWhere('section', $section->section);
    }
    $managedUsers = $managedUsersQuery->get();

    // Filtered objectives: only load if a user has been selected
    $objectives = collect(); // empty collection by default
    if ($request->filled('selected_user')) {
        $objectives = Objective::with(['user', 'period', 'target'])
            ->where('user_id', $request->selected_user)
            ->get();
    }

    return view('objectives.index', compact('objectives', 'managedUsers'));
}

    

    
    // Show the form for creating a new objective
    public function create()
    {
        $user = auth()->user(); // get the logged-in user
        $periods = Period::all();
    
        // Only fetch targets for the authenticated user
        $targets = Target::where('user_id', $user->id)->get();
    
        return view('objectives.create', compact('user', 'periods', 'targets'));
    }
    

   // Store a newly created objective in storage
public function store(Request $request)
{
    $request->validate([
        'target_id' => 'required|exists:targets,id',
        'objective' => 'required|string'
    ]);

    // Get the selected target to inherit user and period
    $target = Target::findOrFail($request->target_id);

    Objective::create([
        'user_id'   => $target->user_id,   // match the target's user
        'period_id' => $target->period_id, // match the target's period
        'target_id' => $request->target_id,
        'objective' => $request->objective,
    ]);

    // Redirect to the user's performance page for that period
    return redirect()->route('user.performance.show', ['period' => $target->period_id])
                     ->with('success', 'Objective created successfully.');
}

    
    // Display the specified objective
    public function show(Objective $objective)
    {
        return view('objectives.show', compact('objective'));
    }

    // Show the form for editing the specified objective
    public function edit(Objective $objective)
    {
        $user = auth()->user(); // logged-in user
        $periods = Period::all();
        $targets = Target::where('user_id', $user->id)->get();
        return view('objectives.edit', compact('objective',  'periods', 'targets'));
    }

    // Update the specified objective in storage
    public function update(Request $request, Objective $objective)
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'target_id' => 'required|exists:targets,id',
            'objective' => 'required|string',
            'actions'   => 'nullable|string',
            'half_year_comment' => 'nullable|string',
            'annual_comment' => 'nullable|string',
            'half_year_rating' => 'nullable|string',
            'annual_rating' => 'nullable|string',
        ]);

        $objective->update($request->all());

        $userId = $objective->user_id; // Ensure this field exists in the initiatives table
        $periodId = $objective->period_id;

        return redirect()->route('user.performance.show', ['period' => $periodId])->with('success', 'objective updated successfully.');
    }

    // Remove the specified objective from storage
    public function destroy(Objective $objective)
    {
        $objective->delete();
        $userId = $objective->user_id; // Ensure this field exists in the initiatives table
        $periodId = $objective->period_id;

        return redirect()->route('user.performance.show', ['period' => $periodId])->with('success', 'objective deleted successfully.');
        
    }

    public function managerUpdate(Request $request, Objective $objective)
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'target_id' => 'required|exists:targets,id',
            'objective' => 'required|string',
            'actions'   => 'nullable|string',
            'half_year_comment' => 'nullable|string',
            'annual_comment' => 'nullable|string',
            'half_year_rating' => 'nullable|string',
            'annual_rating' => 'nullable|string',
        ]);

        $objective->update($request->all());

         return redirect()->route('manager.users.index')->with('success', 'Objective updated successfully.');
    }

    public function managerEdit(Objective $objective)
    {
        $users = User::all();
        $periods = Period::all();
        $targets = Target::all();
        return view('objectives.manager.edit', compact('objective', 'users', 'periods', 'targets'));
    }

}
