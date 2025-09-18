<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use App\Models\Period;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;

class PurposeController extends Controller
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
    
        // Purposes are loaded only if a user is selected
        $purposes = collect(); // empty by default
        if ($request->filled('selected_user')) {
            $purposes = Purpose::with('period', 'user')
                ->where('user_id', $request->selected_user)
                ->get();
        }
    
        return view('purposes.index', compact('purposes', 'managedUsers'));
    }
    

  

    public function create()
    {
        // Fetch all periods and users to show in the create form
        $periods = Period::all();
        $users = User::all(); // Fetch all users
        return view('purposes.create', compact('periods', 'users'));
    }

    


    public function show(Purpose $purpose)
    {
        // Show a specific purpose along with the associated user and period
        return view('purposes.show', compact('purpose'));
    }


    public function edit(Purpose $purpose)
    {
        // Fetch all periods and users to show in the edit form
        $periods = Period::all(); // Fetch all periods
        $users = User::all(); // Fetch all users
        return view('purposes.edit', compact('purpose', 'periods'));
    }



    public function update(Request $request, Purpose $purpose)
{
    // Validate the incoming request
    $request->validate([
      
        'purpose' => 'required|string',
    ]);

    // Update the purpose with the new data
    $purpose->update($request->all());

    return redirect()->route('user.performance.index')->with('success', 'Purpose updated successfully.');
}


    public function destroy(Purpose $purpose)
    {
        // Delete the purpose
        $purpose->delete();

        return redirect()->route('purposes.index')->with('success', 'Purpose deleted successfully.');
    }




    public function managerUpdate(Request $request, Purpose $purpose)
    {
        // Validate the incoming request
        $request->validate([
          
            'purpose' => 'required|string',
        ]);
    
        // Update the purpose with the new data
        $purpose->update($request->all());
    
        return redirect()->route('manager.users.index')->with('success', 'Purpose updated successfully.');
    }


    public function managerEdit(Purpose $purpose)
{
    // Managers can edit user purposes
    $periods = Period::all();
    return view('purposes.manager.edit', compact('purpose', 'periods'));
}
    

    
}


