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
        $period = Period::latest()->first();
    
        // ✅ Get ALL subordinates of the logged-in user
        $managedUsersQuery = $user->subordinates();
    
        // ✅ Only include users with "Pending" approvals
        $managedUsers = $managedUsersQuery->whereHas('approvals', function ($query) {
            $query->where('status', 'Pending');
        })->get();
    
        return view('manager.users', compact('managedUsers', 'period'));
    }


    public function target()
    {
        $user = auth()->user();
        $period = Period::latest()->first();
    
        // ✅ Get ALL subordinates of the logged-in user
        $managedUsersQuery = $user->subordinates();
    
        // ✅ Only include users with "Pending" approvals
        $managedUsers = $managedUsersQuery->whereHas('approvals', function ($query) {
            $query->where('status', 'Approved');
        })->get();
    
        return view('manager.departmentaltarget', compact('managedUsers', 'period'));
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
    
    public function reviewer()
    {
        $user = auth()->user();
    
        // ✅ Get all users supervised by the logged-in user
        $managedUsersQuery = User::where('supervisor_id', $user->id)
            ->where('id', '!=', $user->id); // exclude self just in case
    
        // ✅ Only include users who HAVE authorisations with "Authorized" status
        $managedUsers = $managedUsersQuery->whereHas('authorisations', function ($query) {
            $query->where('status', 'Authorized');
        })->get();
    
        return view('manager.reviewerdash', compact('managedUsers'));
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



    public function authorisation(User $user, $periodId)
    {
        $authorisation = Authorisation::where('user_id', $user->id)
                                      ->where('period_id', $periodId)
                                      ->firstOrFail();
    
        $authorisation->update([
            'status' => 'Authorized',
            'authorised_by' => auth()->id(), // Set the approver
        ]);
    
        return redirect()->route('manager.appraisal.show', ['user' => $user->id])
                         ->with('success', 'User approved successfully.');
    }
    

    public function review(User $user, $periodId)
    {

        $request->validate([
            'reviewercomment' => 'required|string|max:1000',
        ]);

        $authorisation = Authorisation::where('user_id', $user->id)
                                      ->where('period_id', $periodId)
                                      ->firstOrFail();
    
        $authorisation->update([
            'status' => 'Reviewed',
            'reviewercomment' => $request->reviewercomment,
            'reviewed_by' => auth()->id(), // Set the approver
        ]);
    
        return redirect()->route('manager.review.show', ['user' => $user->id])
                         ->with('success', 'User reviewed successfully.');
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


public function reviewreject(Request $request, User $user, $periodId)
{
    $request->validate([
        'reviewercomment' => 'required|string|max:1000',
    ]);

    $authorisation = Authorisation::where('user_id', $user->id)
                                  ->where('period_id', $periodId)
                                  ->firstOrFail();

    $authorisation->update([
        'status' => 'Rejected',
        'reviewercomment' => $request->comment,
    ]);

    return redirect()->route('manager.appraisal.show', ['user' => $user->id])
                     ->with('error', 'User rejected with comment.');
}

public function apreject(Request $request, User $user, $periodId)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    $authorisation = Authorisation::where('user_id', $user->id)
                                  ->where('period_id', $periodId)
                                  ->firstOrFail();

    $authorisation->update([
        'status' => 'Rejected',
        'comment' => $request->comment,
    ]);

    return redirect()->route('manager.appraisal.show', ['user' => $user->id])
                     ->with('error', 'User rejected with comment.');
}


public function show(User $user, $periodId)
{
    $authUser = auth()->user();

    // Check manager permissions
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

    // ✅ Force load a specific period
    $period = Period::findOrFail($periodId);

    // Fetch data tied to this user and this period
    $purposes = Purpose::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $objectives = Objective::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $initiatives = Initiative::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $targets = Target::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $approval = Approval::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->first();

    return view('manager.user_details', compact(
        'user',
        'period',
        'purposes',
        'objectives',
        'initiatives',
        'targets',
        'approval'
    ));
    
}



public function targets(User $user, $periodId)
{
    $authUser = auth()->user();

    // Check manager permissions
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

    // ✅ Force load a specific period
    $period = Period::findOrFail($periodId);

    // Fetch data tied to this user and this period
    $purposes = Purpose::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $objectives = Objective::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $initiatives = Initiative::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $targets = Target::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $approval = Approval::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->first();

    return view('manager.target', compact(
        'user',
        'period',
        'purposes',
        'objectives',
        'initiatives',
        'targets',
        'approval'
    ));


}

        
        //departmental show details with inline edit and authorisation
        public function apshow(User $user)
        {
            // Ensure logged-in manager can view this user
            $authUser = auth()->user();
        
            // TODO: add department/section authorization if needed
        
            $purposes = Purpose::with('period')
                               ->where('user_id', $user->id)
                               ->get();
        
            $objectives = Objective::with(['period', 'target'])
                                   ->where('user_id', $user->id)
                                   ->get();
        
            $initiatives = Initiative::with(['period', 'target', 'objective'])
                                     ->where('user_id', $user->id)
                                     ->get();
        
            $authorisations = Authorisation::with('period')
                                           ->where('user_id', $user->id)
                                           ->get();
        
            return view('manager.appraisal', compact(
                'user',
                'purposes',
                'objectives',
                'initiatives',
                'authorisations'
            ));
        }
        
        

           //departmental show details with inline edit and authorisation
           public function reviewershow(User $user)
           {
               // Ensure logged-in manager can view this user
               $authUser = auth()->user();
           
               // TODO: add department/section authorization if needed
           
               $purposes = Purpose::with('period')
                                  ->where('user_id', $user->id)
                                  ->get();
           
               $objectives = Objective::with(['period', 'target'])
                                      ->where('user_id', $user->id)
                                      ->get();
           
               $initiatives = Initiative::with(['period', 'target', 'objective'])
                                        ->where('user_id', $user->id)
                                        ->get();
           
               $authorisations = Authorisation::with('period')
                                              ->where('user_id', $user->id)
                                              ->get();
           
               return view('manager.reviewer', compact(
                   'user',
                   'purposes',
                   'objectives',
                   'initiatives',
                   'authorisations'
               ));
           }
           
        
    }
    


