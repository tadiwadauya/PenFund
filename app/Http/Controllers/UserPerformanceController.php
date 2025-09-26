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
use App\Models\Task;
use Illuminate\Http\Request;

class UserPerformanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get all periods where the user has any purpose/objective/initiative
        $periods = Period::whereHas('purposes', fn($q) => $q->where('user_id', $user->id))
                         ->orWhereHas('objectives', fn($q) => $q->where('user_id', $user->id))
                         ->orWhereHas('initiatives', fn($q) => $q->where('user_id', $user->id))
                         ->get();

        return view('user.performance.index', compact('periods'));
    }

    // Show data for a specific period
    public function show($periodId)
    {
        $user = auth()->user()->load('supervisor','reviewer'); // ✅ eager load supervisor
    
        $period = Period::findOrFail($periodId);
    
        $purposes = Purpose::where('user_id', $user->id)
            ->where('period_id', $periodId)
            ->get();
    
            $objectives = Objective::where('user_id', $user->id)
            ->where('period_id', $periodId)
            ->whereHas('target') // ✅ only fetch objectives that have a target
            ->get();

            $tasks = Task::where('user_id', $user->id)
            ->where('period_id', $periodId)
            ->get();

            $targets = Target::where('user_id', $user->id)
            ->where('period_id', $periodId)
            ->get();
    
        $initiatives = Initiative::where('user_id', $user->id)
            ->where('period_id', $periodId)
            ->get();
    
        $approval = Approval::where('user_id', $user->id)
            ->where('period_id', $periodId)
            ->first();
    
        return view('user.performance.show', compact(
            'user',
            'period',
            'purposes',
            'objectives',
            'tasks',
            'initiatives',
            'targets',
            'approval'
        ));
    }
    
    

    // Submit for approval
    public function submitForApproval($periodId)
{
    $user = auth()->user();

    // Check if already submitted
    $existing = Approval::where('user_id', $user->id)
        ->where('period_id', $periodId)
        ->first();

    if ($existing) {
        if ($existing->status === 'Rejected') {
            // ✅ Allow resubmission by updating status back to Pending
            $existing->update([
                'status' => 'Pending',
                'reject_reason' => null, // Optional: clear rejection reason
            ]);

            return redirect()->back()->with('success', 'Resubmitted for approval successfully.');
        }

        // If not rejected, block re-submission
        return redirect()->back()->with('error', 'This period is already submitted for approval.');
    }

    // ✅ First time submission
    Approval::create([
        'user_id' => $user->id,
        'period_id' => $periodId,
        'status' => 'Pending',
    ]);

    return redirect()->back()->with('success', 'Submitted for approval successfully.');
}

}
