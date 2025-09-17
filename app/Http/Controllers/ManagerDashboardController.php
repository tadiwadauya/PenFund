<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\User;
use App\Models\Period;
use App\Models\Target;
use App\Models\Initiative;
use App\Models\Department;
use App\Models\Section;
use App\Models\Purpose;
use App\Models\Approval;
use App\Models\Authorisation;
use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        // ✅ Get all users supervised by the logged-in user
        $managedUsersQuery = User::where('supervisor_id', $user->id)
            ->where('id', '!=', $user->id); // exclude self just in case
    
        // ✅ Only include users with "Pending" approvals
        $managedUsers = $managedUsersQuery->whereHas('approvals', function ($query) {
            $query->where('status', 'Pending');
        })->get();
    
        return view('manager.users', compact('managedUsers'));
    }
    
    // deparmental apraisals to approved
    public function appraisal()
    {
        $user = auth()->user();
    
        // ✅ Get all users supervised by the logged-in user
        $managedUsersQuery = User::where('supervisor_id', $user->id)
            ->where('id', '!=', $user->id); // exclude self just in case
    
        // ✅ Only include users who HAVE authorisations with "Pending" status
        $managedUsers = $managedUsersQuery->whereHas('authorisations', function ($query) {
            $query->where('status', 'Pending');
        })->get();
    
        return view('manager.dashboardap', compact('managedUsers'));
    }
    
    public function approve(User $user, $periodId)
    {
        $approval = Approval::where('user_id', $user->id)
                            ->where('period_id', $periodId)
                            ->firstOrFail();
    
        $approval->update([
            'status' => 'Approved',
            'approved_by' => auth()->id() // Set the approved_by to the ID of the currently authenticated user
        ]);
    
        return redirect()->route('manager.users.index')->with('success', 'User approved successfully.');
    }

public function reject(Request $request, User $user, $periodId)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    $approval = Approval::where('user_id', $user->id)
                        ->where('period_id', $periodId)
                        ->firstOrFail();

    $approval->update([
        'status' => 'Rejected',
        'comment' => $request->comment,
    ]);

    return redirect()->route('manager.users.index')
        ->with('error', 'User rejected with comment.');
}


    
        public function show(User $user)
        {
            // Ensure logged-in manager can view this user
            $authUser = auth()->user();
    
            $department = Department::where('manager', $authUser->id)->first();
            $section = Section::where('manager', $authUser->id)->first();
    
            $isAllowed = false;
            if ($department && $user->department === $department->department) {
                $isAllowed = true;
            }
            if ($section && $user->section === $section->section) {
                $isAllowed = true;
            }
    
            if (!$isAllowed) {
                abort(403, 'You are not authorized to view this user.');
            }
    
            // Fetch related data
            $purposes = Purpose::with('period')->where('user_id', $user->id)->get();
            $objectives = Objective::with(['period', 'target'])->where('user_id', $user->id)->get();
            $initiatives = Initiative::with(['period', 'target', 'objective'])->where('user_id', $user->id)->get();
    
            return view('manager.user_details', compact('user', 'purposes', 'objectives', 'initiatives'));
        }
        
        //departmental show details with inline edit and authorisation
        public function apshow($periodId)
        {
            // Ensure logged-in manager can view this user
            $user = auth()->user();

            $period = Period::findOrFail($periodId);
    
            $purposes = Purpose::where('user_id', $user->id)->where('period_id', $periodId)->get();
            $objectives = Objective::where('user_id', $user->id)->where('period_id', $periodId)->get();
            $initiatives = Initiative::where('user_id', $user->id)->where('period_id', $periodId)->get();
    
            // Check if Authorisation exists
            $authorisation = Authorisation::where('user_id', $user->id)->where('period_id', $periodId)->first();
    
            return view('manager.appraisal', compact('period', 'purposes', 'objectives', 'initiatives', 'authorisation'));
        }
    }
    


