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

    public function generate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
        ]);
    
        $user = User::find($request->user_id);
        $purposes = Purpose::where('user_id', $user->id)
            ->where('period_id', $request->period_id)
            ->get();
    
        // Get user's department to find the manager
        $department = Department::where('department', $user->department)->first();
        $manager = null;
        $managerJobTitle = null;
        $managerGrade = null;
    
        if ($department && $department->manager) {
            // Fetch the manager's details
            $manager = User::find($department->manager);
            if ($manager) {
                $managerJobTitle = $manager->jobtitle;
                $managerGrade = $manager->grade;
            }
        }
    
        // Fetch targets, objectives, and initiatives
        $targets = Target::all();
        $objectives = Objective::where('user_id', $user->id)
            ->where('period_id', $request->period_id)
            ->with('initiatives') // Eager load initiatives
            ->get();
    
        // Generate PDF
        $pdf = PDF::loadView('pdf.report', compact('user', 'purposes', 'managerJobTitle', 'managerGrade', 'targets', 'objectives'));
        return $pdf->download('report.pdf');
    }
}
