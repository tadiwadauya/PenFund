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
use App\Models\Authorisation;
use App\Models\Rating;
use App\Models\UserStrength;
use App\Models\UserLearningArea;
use App\Models\PerformanceSummary;

use Illuminate\Http\Request;

class PerformanceApraisalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        // Get all periods where the user has an approved appraisal
        $periods = Period::whereHas('approvals', function ($q) use ($user) {
                                $q->where('user_id', $user->id)
                                  ->where('status', 'Approved');
                            })
                            ->with([
                                'approvals' => fn($q) => $q->where('user_id', $user->id),
                                'authorisations' => fn($q) => $q->where('user_id', $user->id),
                            ])
                            ->get();
    
        return view('user.performanceapraisal.index', compact('periods'));
    }
    

    public function show(User $user, $periodId)
{
    $user = auth()->user();
    
    $period = Period::findOrFail($periodId);
    
    $purposes = Purpose::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $objectives = Objective::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $initiatives = Initiative::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->get();

    $authorisation = Authorisation::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->first();

    $sections = \App\Models\EvaluationSection::with([
        'tasks.ratings' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }
    ])->get();

    // Strengths & Learning Areas
    $selfStrengths = UserStrength::where('user_id', $user->id)->where('type', 'self')->get();
    $selfLearning  = UserLearningArea::where('user_id', $user->id)->where('type', 'self')->get();
    $assessorStrengths = UserStrength::where('user_id', $user->id)->where('type', 'assessor')->get();
    $assessorLearning  = UserLearningArea::where('user_id', $user->id)->where('type', 'assessor')->get();

    // Function to convert numeric rating to grade
    $gradeFromNumber = function($num) {
        if ($num >= 5.5) return 'A1';
        if ($num >= 4.5) return 'A2';
        if ($num >= 3.5) return 'B1';
        if ($num >= 2.5) return 'B2';
        if ($num >= 1.5) return 'C1';
        if ($num >= 0.5) return 'C2';
        return '-';
    };

    // Calculate section-level ratings for Balanced Scorecard table
    $sectionRatings = [];
    $sectionTotals = [];
    foreach ($sections as $section) {
        $overallSection = collect($section->tasks)->map(function($task){
            $rating = $task->ratings->first();
            return $rating ? $rating->self_rating : null;
        })->filter()->avg();

        $sectionRatings[] = [
            'name' => $section->name,
            'average' => $overallSection,
            'label' => $overallSection ? $gradeFromNumber($overallSection) : '-'
        ];

        if ($overallSection) $sectionTotals[] = $overallSection;
    }

    $totalPerformanceNotches = count($sectionTotals) ? array_sum($sectionTotals)/count($sectionTotals) : null;
    $totalPerformanceNotchesLabel = $totalPerformanceNotches ? $gradeFromNumber($totalPerformanceNotches) : '-';

    return view('user.performanceapraisal.show', compact(
        'user',
        'period',
        'purposes',
        'objectives',
        'initiatives',
        'authorisation',
        'sections',
        'gradeFromNumber',
        'selfStrengths',
        'selfLearning',
        'assessorStrengths',
        'assessorLearning',
        'sectionRatings',
        'totalPerformanceNotchesLabel'
    ));
}

    

    // Submit for Authorisation

    public function submitForAuthorisation($periodId)
    {
        $user = auth()->user();
    
        // Check if already submitted
        $existing = Authorisation::where('user_id', $user->id)
            ->where('period_id', $periodId)
            ->first();
    
        if ($existing) {
            if ($existing->status === 'Rejected') {
                // ✅ Allow resubmission by updating status back to Pending
                $existing->update([
                    'status' => 'Pending',
                    'reject_reason' => null, // Optional: clear rejection reason
                ]);
    
                return redirect()->back()->with('success', 'Resubmitted for authorisation successfully.');
            }
    
            // If not rejected, block re-submission
            return redirect()->back()->with('error', 'This period is already submitted for authorisation.');
        }
          // ✅ First time submission
          Authorisation::create([
        'user_id' => $user->id,
        'period_id' => $periodId,
        'status' => 'Pending',
    ]);

    return redirect()->back()->with('success', 'Submitted for approval successfully.');

}

public function updateSelf(Request $request, Rating $rating)
{
    // Ensure the logged-in user can only update their own rating
    if ($rating->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    $request->validate([
        'self_rating' => 'nullable|integer|min:1|max:6',
        'self_comment' => 'nullable|string|max:1000',
    ]);

    $rating->update([
        'self_rating' => $request->self_rating,
        'self_comment' => $request->self_comment,
    ]);

    return redirect()->back()->with('success', 'Rating updated successfully!');
}

/**
 * Save all self ratings at once
 */
public function saveAll(Request $request)
{
    $ratingsData = $request->input('ratings', []);

    foreach ($ratingsData as $taskId => $data) {
        // Ensure rating exists
        $rating = \App\Models\Rating::updateOrCreate(
            [
                'task_id' => $taskId,
                'user_id' => auth()->id(),
            ],
            [] // don't overwrite anything yet
        );

        // Conditionally update fields only if present in request
        if (array_key_exists('self_rating', $data)) {
            $rating->self_rating = $data['self_rating'];
        }
        if (array_key_exists('self_comment', $data)) {
            $rating->self_comment = $data['self_comment'];
        }
        if (array_key_exists('assessor_rating', $data)) {
            $rating->assessor_rating = $data['assessor_rating'];
        }
        if (array_key_exists('assessor_comment', $data)) {
            $rating->assessor_comment = $data['assessor_comment'];
        }

        $rating->save();
    }
    return redirect()->back()->with('success', 'All ratings saved successfully!');
}

public function performanceSummaries()
{
    // Get all reviewed summaries
    $summaries = PerformanceSummary::with(['user', 'period'])
        ->whereHas('user.authorisations', function ($query) {
            $query->where('status', 'Reviewed');
        })
        ->get();

    // Group summaries by department and section
    $byDepartment = $summaries->groupBy(fn($s) => $s->user->department ?? 'No Department');
    $bySection = $summaries->groupBy(fn($s) => $s->user->section ?? 'No Section');

    // Function to convert grade to numeric for averaging
    $gradeToNumeric = fn($grade) => match($grade) {
        'A1'=>6, 'A2'=>5, 'B1'=>4, 'B2'=>3, 'C1'=>2, 'C2'=>1, default=>0
    };
    $numericToGrade = fn($num) => match(true){
        $num>=5.5=>'A1',
        $num>=4.5=>'A2',
        $num>=3.5=>'B1',
        $num>=2.5=>'B2',
        $num>=1.5=>'C1',
        $num>=0.5=>'C2',
        default => '-'
    };

    // Overall department performance
    $departmentNames = [];
    $departmentGradesNumeric = [];
    $departmentGrades = [];

    foreach ($byDepartment as $dept => $deptSummaries) {
        $avg = collect($deptSummaries)->avg(fn($s)=>$gradeToNumeric($s->total_assessor_label));
        $departmentNames[] = $dept;
        $departmentGradesNumeric[] = round($avg,2);
        $departmentGrades[$dept] = $numericToGrade($avg);
    }

    // Overall section performance
    $sectionGrades = [];
    foreach ($bySection as $section => $secSummaries) {
        $avg = collect($secSummaries)->avg(fn($s)=>$gradeToNumeric($s->total_assessor_label));
        $sectionGrades[$section] = $numericToGrade($avg);
    }

    // Organization performance per period
    $periods = \App\Models\Period::orderBy('year')->get();
    $periodLabels = [];
    $periodValues = [];

    foreach ($periods as $period) {
        $periodSummaries = PerformanceSummary::with('user')
            ->where('period_id', $period->id)
            ->whereHas('user.authorisations', fn($q)=>$q->where('status','Reviewed'))
            ->get();

        $avg = $periodSummaries->avg(fn($s)=>$gradeToNumeric($s->total_assessor_label));
        $periodLabels[] = $period->name ?? $period->year;
        $periodValues[] = round($avg,2);
    }

    return view('manager.performance_summaries.index', compact(
        'summaries',
        'byDepartment',
        'bySection',
        'departmentNames',
        'departmentGradesNumeric',
        'departmentGrades',
        'sectionGrades',
        'periodLabels',
        'periodValues'
    ));
}







}
