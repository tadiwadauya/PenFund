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

    return $pdf->download('performance_contract.pdf');
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

    // Flatten initiatives for table
    $initiatives = $objectives->flatMap(function ($objective) {
        return $objective->initiatives->map(function ($initiative) use ($objective) {
            $initiative->target = $objective->target;
            return $initiative;
        });
    });

    // Fetch approvals and superiors
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

    // Fetch sections + tasks + ratings
    $sections = \App\Models\EvaluationSection::with(['tasks.ratings' => function($q) use ($user) {
        $q->where('user_id', $user->id);
    }])->get();

    // Compute section-level ratings for Balanced Scorecard
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

    // Fetch strengths & learning areas
    $selfStrengths = \App\Models\UserStrength::where('user_id', $user->id)
        ->where('type', 'self')
        ->get();

    $selfLearning  = \App\Models\UserLearningArea::where('user_id', $user->id)
        ->where('type', 'self')
        ->get();

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
        'totalPerformanceNotchesLabel',
        'gradeFromNumber',
        'selfStrengths',
        'selfLearning'
    ));

    return $pdf->download("performance_appraisal_{$user->id}_{$period->year}.pdf");
}




}
