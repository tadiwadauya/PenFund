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
use App\Models\EvaluationSection;
use App\Models\UserStrength;
use App\Models\UserLearningArea;
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
    
        // ✅ Get the current active period
        $period = Period::latest()->first(); 
        // or however you determine the current appraisal period
    
        return view('manager.dashboardap', compact('managedUsers', 'period'));
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
public function apshow(User $user, $periodId)
{
    $authUser = auth()->user();
    $period = Period::findOrFail($periodId);

    $purposes = Purpose::with('period')->where('user_id', $user->id)->where('period_id', $periodId)->get();
    $objectives = Objective::with(['period', 'target'])->where('user_id', $user->id)->where('period_id', $periodId)->get();
    $initiatives = Initiative::with(['period', 'target', 'objective'])->where('user_id', $user->id)->where('period_id', $periodId)->get();
    $authorisations = Authorisation::with('period')->where('user_id', $user->id)->where('period_id', $periodId)->get();

    $sections = \App\Models\EvaluationSection::with(['tasks.ratings' => fn($q) => $q->where('user_id', $user->id)])->get();

    // My Ratings
    $sectionRatingsForMyRatings = [];
    foreach ($sections as $section) {
        $avgSelf = collect($section->tasks)->map(fn($task) => $task->ratings->first()?->self_rating)->filter()->avg();
        $sectionRatingsForMyRatings[] = [
            'section' => $section,
            'avgSelf' => $avgSelf,
            'label' => $avgSelf !== null ? $this->gradeFromNumber($avgSelf) : '-',
        ];
    }

    $selfStrengths = UserStrength::where('user_id', $user->id)->where('type', 'self')->get();
    $selfLearning = UserLearningArea::where('user_id', $user->id)->where('type', 'self')->get();
    $assessorStrengths = UserStrength::where('user_id', $user->id)->where('type', 'assessor')->get();
    $assessorLearning = UserLearningArea::where('user_id', $user->id)->where('type', 'assessor')->get();

    // Overall summary
    $sectionRatings = [];
    $sectionAverages = [];
    $sectionAssessorAvgs = [];
    foreach ($sections as $section) {
        $overallSelf = collect($section->tasks)->map(fn($t) => $t->ratings->first()?->self_rating)->filter()->avg();
        $overallAssessor = collect($section->tasks)->map(fn($t) => $t->ratings->first()?->assessor_rating)->filter()->avg();

        $sectionRatings[] = [
            'name' => $section->name,
            'average' => $overallSelf,
            'label' => $overallSelf !== null ? $this->gradeFromNumber($overallSelf) : '-',
            'assessor_average' => $overallAssessor,
            'assessor_label' => $overallAssessor !== null ? $this->gradeFromNumber($overallAssessor) : '-',
        ];
        if ($overallSelf !== null) $sectionAverages[] = $overallSelf;
        if ($overallAssessor !== null) $sectionAssessorAvgs[] = $overallAssessor;
    }

    $totalSelfLabel = count($sectionAverages) ? $this->gradeFromNumber(array_sum($sectionAverages)/count($sectionAverages)) : '-';
    $totalAssessorLabel = count($sectionAssessorAvgs) ? $this->gradeFromNumber(array_sum($sectionAssessorAvgs)/count($sectionAssessorAvgs)) : '-';

    return view('manager.appraisal', compact(
        'user','period','purposes','objectives','initiatives','authorisations',
        'sections','sectionRatingsForMyRatings','selfStrengths','selfLearning',
        'assessorStrengths','assessorLearning','sectionRatings','totalSelfLabel','totalAssessorLabel'
    
))->with('gradeFromNumber', function($num) {
    return $this->gradeFromNumber($num);
});
}

/**
 * Convert numeric average to grade label.
 */
private function gradeFromNumber($num)
{
    if ($num === null) return '-';
    if ($num >= 5.5) return 'A1';
    if ($num >= 4.5) return 'A2';
    if ($num >= 3.5) return 'B1';
    if ($num >= 2.5) return 'B2';
    if ($num >= 1.5) return 'C1';
    if ($num >= 0.5) return 'C2';
    return '-';
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



           public function saveAssessorRatings(Request $request)
           {
               $ratingsData = $request->input('ratings', []);
               $userId = $request->input('user_id'); // The user being rated
           
               foreach ($ratingsData as $taskId => $data) {
                   $rating = \App\Models\Rating::firstOrNew([
                       'task_id' => $taskId,
                       'user_id' => $userId,
                   ]);
           
                   // Only update assessor fields
                   if (isset($data['assessor_rating'])) {
                       $rating->assessor_rating = $data['assessor_rating'];
                   }
                   if (isset($data['assessor_comment'])) {
                       $rating->assessor_comment = $data['assessor_comment'];
                   }
           
                   $rating->save();
               }
           
               return redirect()->back()->with('success', 'Assessor ratings saved successfully!');
           }
           

public function updateSelf(Request $request, Rating $rating)
{
    // Ensure the logged-in user can only update their own rating
    if ($rating->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    $request->validate([
        'assessor_rating' => 'nullable|integer|min:1|max:6',
        'assessor_comment' => 'nullable|string|max:1000',
    ]);

    $rating->update([
        'assessor_rating' => $request->assessor_rating,
        'assessor_comment' => $request->assessor_comment,
    ]);

    return redirect()->back()->with('success', 'Rating updated successfully!');
}
        
    }

    


