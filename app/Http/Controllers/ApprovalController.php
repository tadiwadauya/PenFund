<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $manager = auth()->user();
    
        // Check if manager is department or section head
        $department = Department::where('manager', $manager->id)->first();
        $section = Section::where('manager', $manager->id)->first();
    
        if (!$department && !$section) {
            abort(403, 'You are not authorized to view this page.');
        }
    
        // Get users in manager's department/section
        $managedUsersQuery = User::query();
        if ($department) {
            $managedUsersQuery->orWhere('department', $department->department);
        }
        if ($section) {
            $managedUsersQuery->orWhere('section', $section->section);
        }
    
        $managedUsers = $managedUsersQuery->get();
    
        // Only users that are pending approval
        $pendingUsers = $managedUsers->filter(function ($user) {
            return $user->approvals()
                ->where('status', 'pending')
                ->exists();
        });
    
        return view('approvals.index', compact('pendingUsers'));
    }
    

    public function show(Approval $approval)
    {
        $user = $approval->user;

        // load related data for that year
        $purposes = Purpose::with('period')
            ->where('user_id', $user->id)
            ->where('period_id', $approval->period_id)
            ->get();

        $objectives = Objective::with(['period', 'target'])
            ->where('user_id', $user->id)
            ->where('period_id', $approval->period_id)
            ->get();

        $initiatives = Initiative::with(['period', 'target', 'objective'])
            ->where('user_id', $user->id)
            ->where('period_id', $approval->period_id)
            ->get();

        return view('approvals.show', compact('approval', 'user', 'purposes', 'objectives', 'initiatives'));
    }

    public function approve(Approval $approval)
    {
        $approval->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'comment' => null
        ]);

        return redirect()->route('approvals.index')->with('success', 'Approved successfully.');
    }

    public function reject(Request $request, Approval $approval)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $approval->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'comment' => $request->comment
        ]);

        return redirect()->route('approvals.index')->with('success', 'Rejected successfully.');
    }
}
