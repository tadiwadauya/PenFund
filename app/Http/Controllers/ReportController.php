<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Period;
use App\Models\Purpose;
use App\Models\Target;
use App\Models\Objective;
use App\Models\Initiative;
use App\Models\Department;
use App\Models\Authorisation;
use App\Models\Approval;
use Illuminate\Http\Request;

use PDF; // Use a PDF library like dompdf or laravel-snappy

class ReportController extends Controller
{
    public function create()
    {
        $users = User::all();
        $periods = Period::all();
        return view('report', compact('users', 'periods'));
    }

    public function mycreate()
    {
        $users = User::all();
        $periods = Period::all();
        return view('myreport', compact('users', 'periods'));
    }

    public function apcreate()
    {
        $users = User::all();
        $periods = Period::all();
        return view('myappraisal', compact('users', 'periods'));
    }

    public function generate(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'period_id' => 'required|exists:periods,id',
    ]);

    // Get the employee
    $user = User::findOrFail($request->user_id);
    $period = Period::findOrFail($request->period_id);

    // Fetch purposes for the user
    $purposes = Purpose::where('user_id', $user->id)
        ->where('period_id', $request->period_id)
        ->get();

    // Fetch objectives (with initiatives + target)
    $objectives = Objective::where('user_id', $user->id)
        ->where('period_id', $request->period_id)
        ->with(['initiatives', 'target'])
        ->get();

    // Fetch approvals with approver relation
    $approvals = Approval::with('user') // the one being approved
        ->where('user_id', $user->id)
        ->where('period_id', $request->period_id)
        ->where('status', 'Approved')
        ->get();

    // Fetch superiors who approved (based on approved_by field)
    $superiors = User::whereIn('id', $approvals->pluck('approved_by'))->get();

    // Generate PDF
    $pdf = PDF::loadView('pdf.report', compact(
        'user',
        'purposes',
        'objectives',
        'period',
        'approvals',
        'superiors'
    ));

    return $pdf->download('performance_targets.pdf');
}
public function apgenerate(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'period_id' => 'required|exists:periods,id',
    ]);

    $user = User::findOrFail($request->user_id);
    $period = Period::findOrFail($request->period_id);

    // Fetch purposes for the user
    $purposes = Purpose::where('user_id', $user->id)
        ->where('period_id', $period->id)
        ->get();

    // Fetch objectives with initiatives + targets
    $objectives = Objective::where('user_id', $user->id)
        ->where('period_id', $period->id)
        ->with(['initiatives', 'target'])
        ->get();

    // Flatten initiatives
    $initiatives = $objectives->flatMap(function ($objective) {
        return $objective->initiatives->map(function ($initiative) use ($objective) {
            $initiative->target = $objective->target;
            return $initiative;
        });
    });

    // Fetch approvals
    $authorisations = Authorisation::with('user')
        ->where('user_id', $user->id)
        ->where('period_id', $request->period_id)
        ->where('status', 'Authorized')
        ->get();

    $superiors = User::whereIn('id', $authorisations->pluck('authorised_by'))->get();

    // Rating labels
    $ratingLabels = [
        6 => 'A1 - Outstanding performance. High levels of expertise',
        5 => 'A2 - Consistently exceeds requirements',
        4 => 'B1 - Meets requirements. Occasionally exceeds them',
        3 => 'B2 - Meets requirements',
        2 => 'C1 - Partially meets requirements. Improvement required',
        1 => 'C2 - Unacceptable. Well below standard required',
    ];

    // Helper function
    $gradeFromNumber = function($num) {
        if ($num >= 5.5) return 'A1';
        if ($num >= 4.5) return 'A2';
        if ($num >= 3.5) return 'B1';
        if ($num >= 2.5) return 'B2';
        if ($num >= 1.5) return 'C1';
        if ($num >= 0.5) return 'C2';
        return '-';
    };

    // Fetch sections + tasks + ratings
    $sections = \App\Models\EvaluationSection::with(['tasks.ratings' => function($q) use ($user) {
        $q->where('user_id', $user->id);
    }])->get();

    // Compute section ratings
    $sectionRatings = [];
    $staffTotals = []; $assessorTotals = []; $reviewerTotals = [];

    foreach ($sections as $section) {
        $selfTotal = $selfCount = 0;
        $assessorTotal = $assessorCount = 0;
        $reviewerTotal = $reviewerCount = 0;
        $reviewerComments = [];

        foreach ($section->tasks as $task) {
            $rating = $task->ratings->first();

            if ($rating && $rating->self_rating) {
                $selfTotal += $rating->self_rating;
                $selfCount++;
            }
            if ($rating && $rating->assessor_rating) {
                $assessorTotal += $rating->assessor_rating;
                $assessorCount++;
            }
            if ($rating && $rating->reviewer_rating) {
                $reviewerTotal += $rating->reviewer_rating;
                $reviewerCount++;
            }
            if ($rating && $rating->reviewer_comment) {
                $reviewerComments[] = $rating->reviewer_comment;
            }
        }

        $sectionRatings[] = [
            'name'             => $section->name,
            'staff_label'      => $selfCount > 0 ? $gradeFromNumber($selfTotal / $selfCount) : 'Not Rated',
            'assessor_label'   => $assessorCount > 0 ? $gradeFromNumber($assessorTotal / $assessorCount) : 'Not Rated',
            'reviewer_label'   => $reviewerCount > 0 ? $gradeFromNumber($reviewerTotal / $reviewerCount) : 'Not Rated',
            'reviewer_comment' => count($reviewerComments) ? implode('; ', $reviewerComments) : '-',
        ];

        if ($selfCount > 0) $staffTotals[] = $selfTotal / $selfCount;
        if ($assessorCount > 0) $assessorTotals[] = $assessorTotal / $assessorCount;
        if ($reviewerCount > 0) $reviewerTotals[] = $reviewerTotal / $reviewerCount;
    }

    // Totals
    $totalPerformanceNotchesStaff    = count($staffTotals) ? $gradeFromNumber(array_sum($staffTotals) / count($staffTotals)) : 'N/A';
    $totalPerformanceNotchesAssessor = count($assessorTotals) ? $gradeFromNumber(array_sum($assessorTotals) / count($assessorTotals)) : 'N/A';
    $totalPerformanceNotchesReviewer = count($reviewerTotals) ? $gradeFromNumber(array_sum($reviewerTotals) / count($reviewerTotals)) : 'N/A';

    // Strengths & learning
    $selfStrengths = \App\Models\UserStrength::where('user_id', $user->id)->where('type', 'self')->get();
    $selfLearning = \App\Models\UserLearningArea::where('user_id', $user->id)->where('type', 'self')->get();

    // Generate PDF
    $pdf = PDF::loadView('pdf.reportappraisal', compact(
        'user',
        'period',
        'purposes',
        'objectives',
        'initiatives',
        'authorisations',
        'superiors',
        'ratingLabels',
        'sections',
        'sectionRatings',
        'totalPerformanceNotchesStaff',
        'totalPerformanceNotchesAssessor',
        'totalPerformanceNotchesReviewer',
        'gradeFromNumber',
        'selfStrengths',
        'selfLearning'
    ));

    return $pdf->download("performance_appraisal_{$user->id}_{$period->year}.pdf");
}






}
