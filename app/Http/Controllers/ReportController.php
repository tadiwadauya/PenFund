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

    // Flatten all initiatives into a single collection for the table
    $initiatives = $objectives->flatMap(function ($objective) {
        return $objective->initiatives->map(function ($initiative) use ($objective) {
            // Attach target reference for inline editing
            $initiative->target = $objective->target;
            return $initiative;
        });
    });

    // Fetch approvals with approver relation
    $authorisations = Authorisation::with('user')
        ->where('user_id', $user->id)
        ->where('period_id', $request->period_id)
        ->where('status', 'Authorized')
        ->get();

    // Fetch superiors who approved
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

    // Calculate averages per target
    $averages = $objectives->map(function ($objective) use ($ratingLabels) {
        $avg = $objective->initiatives->avg('rating');
        $supervisorAvg = $objective->initiatives->avg('supervisorrating');

        return [
            'target_name' => $objective->target->target_name ?? 'No Target',
            'average' => $avg,
            'label' => $avg ? ($ratingLabels[round($avg)] ?? 'Not Rated') : 'Not Rated',
            'supervisor_average' => $supervisorAvg,
            'supervisor_label' => $supervisorAvg ? ($ratingLabels[round($supervisorAvg)] ?? 'Not Rated') : 'Not Rated',
        ];
    })->filter(fn($a) => $a['average'] || $a['supervisor_average']);

    // Overall averages
    $overallAverage = $objectives->flatMap->initiatives->avg('rating');
    $overallLabel = $overallAverage ? ($ratingLabels[round($overallAverage)] ?? 'Not Rated') : 'Not Rated';

    $overallSupervisorAverage = $objectives->flatMap->initiatives->avg('supervisorrating');
    $overallSupervisorLabel = $overallSupervisorAverage ? ($ratingLabels[round($overallSupervisorAverage)] ?? 'Not Rated') : 'Not Rated';

    // Generate PDF
    $pdf = PDF::loadView('pdf.reportappraisal', compact(
        'user',
        'period',
        'purposes',
        'objectives',
        'initiatives', // 👈 now pass initiatives
        'averages',
        'overallAverage',
        'overallLabel',
        'overallSupervisorAverage',
        'overallSupervisorLabel',
        'authorisations',
        'superiors',
        'ratingLabels' // 👈 pass to Blade
    ));

    return $pdf->download("performance_appraisal_{$user->id}_{$period->year}.pdf");
}



}
