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
    
    public function show($periodId)
    {
        $user = auth()->user();

        $period = Period::findOrFail($periodId);

        $purposes = Purpose::where('user_id', $user->id)->where('period_id', $periodId)->get();
        $objectives = Objective::where('user_id', $user->id)->where('period_id', $periodId)->get();
        $initiatives = Initiative::where('user_id', $user->id)->where('period_id', $periodId)->get();

        // Check if Authorisation exists
        $authorisation = Authorisation::where('user_id', $user->id)->where('period_id', $periodId)->first();

        return view('user.performanceapraisal.show', compact('period', 'purposes', 'objectives', 'initiatives', 'authorisation'));
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
}
