<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Period;
use App\Models\Purpose;
use App\Models\Target;
use App\Models\Objective;
use App\Models\Initiative;
use App\Models\Department;
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
}
