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

        $user = User::findOrFail($request->user_id);

        // Fetch purposes for the user
        $purposes = Purpose::where('user_id', $user->id)
            ->where('period_id', $request->period_id)
            ->get();

        // Fetch objectives (with initiatives + target)
        $objectives = Objective::where('user_id', $user->id)
            ->where('period_id', $request->period_id)
            ->with(['initiatives', 'target']) // Ensure initiatives and targets are loaded
            ->get();

        // Other logic...

        // Generate PDF
        $pdf = PDF::loadView('pdf.report', compact(
            'user', 'purposes', 'objectives' // Pass objectives directly
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

        // Rating labels
        $ratingLabels = [
            6 => 'A1 - Outstanding performance. High levels of expertise',
            5 => 'A2 - Consistently exceeds requirements',
            4 => 'B1 - Meets requirements. Occasionally exceeds them',
            3 => 'B2 - Meets requirements',
            2 => 'C1 - Partially meets requirements. Improvement required',
            1 => 'C2 - Unacceptable. Well below standard required',
        ];

        // Calculate averages per target and label them
        $averages = $objectives->map(function ($objective) use ($ratingLabels) {
            $avg = $objective->initiatives->avg('rating');
            return [
                'target_name' => $objective->target->target_name ?? 'No Target',
                'average' => $avg,
                'label' => $ratingLabels[$avg] ?? 'Not Rated',
            ];
        })->filter(fn($a) => $a['average']); // remove null averages

        // Overall average
        $overallAverage = $objectives->flatMap->initiatives->avg('rating');
        $overallLabel = $ratingLabels[$overallAverage] ?? 'Not Rated';

        // Generate PDF
        $pdf = PDF::loadView('pdf.reportappraisal', compact(
            'user',
            'period',
            'purposes',
            'objectives',
            'averages',
            'overallAverage',
            'overallLabel'
        ));

        return $pdf->download("performance_appraisal_{$user->id}_{$period->year}.pdf");
    }

}
