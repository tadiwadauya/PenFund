<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\User;
use App\Models\Period;
use App\Models\Target;
use App\Models\Initiative;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Http\Request;

class InitiativeController extends Controller
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
    
        // Initiatives are loaded only if a user is selected
        $initiatives = collect(); // empty collection
        if ($request->filled('selected_user')) {
            $initiatives = Initiative::with(['user', 'period', 'target', 'objective'])
                ->where('user_id', $request->selected_user)
                ->get();
        }
    
        return view('initiatives.index', compact('initiatives', 'managedUsers'));
    }
    

    
    // Show the form for creating a new Initiative
    public function create()
    {
        // only list objectives that belong to the logged-in user for the selected period
        $objectives = Objective::where('user_id', auth()->id())->get();
        $targets = Target::all();
    
        return view('initiatives.create', compact('objectives', 'targets'));
    }

    // Store a newly created initiative in storage
    public function store(Request $request)
{
    $request->validate([
        'objective_id' => 'required|exists:objectives,id',
        'target_id'    => 'required|exists:targets,id',
        'initiative'   => 'required|string',
        'budget'       => 'nullable|string',
    ]);

    // ✅ get the objective so we inherit its user + period
    $objective = Objective::findOrFail($request->objective_id);

    $objective->initiatives()->create([
        'user_id'    => $objective->user_id,   // always matches objective’s user
        'period_id'  => $objective->period_id, // always matches objective’s period
        'target_id'  => $request->target_id,
        'initiative' => $request->initiative,
        'budget'     => $request->budget,
        'createdby'  => auth()->user()->name,
    ]);

    return redirect()->back()->with('success', 'Objective created successfully.');
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

        return redirect()->route('user.performance.index')->with('success', 'Initiative updated successfully.');
    }

    public function updateInline(Request $request, $id)
    {
        $request->validate([
            'archieved'         => 'required|boolean',   // must be 0 or 1
            'rating'            => 'nullable|integer|min:1|max:6',
            'supervisorrating'  => 'nullable|integer|min:1|max:6',
            'comment'           => 'nullable|string|max:1000',
        ]);
    
        $initiative = Initiative::findOrFail($id);
    
        // Only update the fields that were actually present in the form
        $data = [
            'archieved' => $request->input('archieved', 0),
            'comment'   => $request->input('comment'),
        ];
    
        if ($request->has('supervisorrating')) {
            $data['supervisorrating'] = $request->input('supervisorrating');
        }
    
        // Only update rating if it exists in the form (other blade)
        if ($request->has('rating')) {
            $data['rating'] = $request->input('rating');
        }
    
        $initiative->update($data);
    
        return redirect()->back()->with('success', 'Initiative updated successfully.');
    }
    
    
    // Remove the specified Initiative from storage
    public function destroy(Initiative $initiative)
    {
        // Get the user ID associated with the initiative
        $userId = $initiative->user_id; // Ensure this field exists in the initiatives table
        $periodId = $initiative->period_id; // Retrieve the period ID associated with the initiative
    
        // Delete the initiative
        $initiative->delete();
    
        // Redirect to the specific user's performance page with a success message
        return redirect()->route('user.performance.show', ['period' => $periodId])->with('success', 'Initiative deleted successfully.');
    }

    public function managerUpdate(Request $request, Initiative $initiative)
    {
        $request->validate([
            'period_id'    => 'required|exists:periods,id',
            'target_id'    => 'required|exists:targets,id',
            'objective_id' => 'required|exists:objectives,id',
            'user_id'      => 'required|exists:users,id',
            'initiative'   => 'required|string',
            'budget'       => 'required|string',
        ]);
    
        $initiative->update($request->all());
    
        return redirect()->route('manager.users.index')->with('success', 'Action updated successfully.');
    }
    
    public function managerEdit(Initiative $initiative)
    {
        $users      = User::all();
        $periods    = Period::all();
        $targets    = Target::all();
        $objectives = Objective::all();
    
        return view('initiatives.manager.edit', compact('initiative', 'users', 'periods', 'targets', 'objectives'));
    }
    
    
}
