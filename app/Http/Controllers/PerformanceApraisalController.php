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
    
        $gradeFromNumber = function($num) {
            if ($num >= 5.5) return 'A1';
            if ($num >= 4.5) return 'A2';
            if ($num >= 3.5) return 'B1';
            if ($num >= 2.5) return 'B2';
            if ($num >= 1.5) return 'C1';
            if ($num >= 0.5) return 'C2';
            return '-';
        };
    
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
            'assessorLearning'
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
        Rating::updateOrCreate(
            [
                'task_id' => $taskId,
                'user_id' => auth()->id(),
            ],
            [
                'self_rating' => $data['self_rating'] ?? null,
                'self_comment' => $data['self_comment'] ?? null,
            ]
        );
    }

    return redirect()->back()->with('success', 'All ratings saved successfully!');
}
}
