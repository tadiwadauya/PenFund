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
use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        $department = Department::where('manager', $user->id)->first();
        $section = Section::where('manager', $user->id)->first();
    
        if (!$department && !$section) {
            abort(403, 'You are not authorized to view this page.');
        }
    
        $managedUsersQuery = User::query();
        if ($department) {
            $managedUsersQuery->orWhere('department', $department->department);
        }
        if ($section) {
            $managedUsersQuery->orWhere('section', $section->section);
        }
    
        // ✅ Only include users with "Pending" approval
        $managedUsers = $managedUsersQuery
            ->whereHas('approvals', function ($query) {
                $query->where('status', 'Pending');
            })
            ->get();
    
        return view('manager.users', compact('managedUsers'));
    }
    
    public function approve(User $user, $periodId)
{
    $approval = Approval::where('user_id', $user->id)
                        ->where('period_id', $periodId)
                        ->firstOrFail();

    $approval->update(['status' => 'Approved']);

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
    }
    


