<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    // List all ratings of the logged-in user
    public function index() {
        $sections = \App\Models\EvaluationSection::with(['tasks.ratings' => function($q){
            $q->where('user_id', auth()->id());
        }])->get();
    
        $gradeFromNumber = function($num) {
            if ($num >= 5.5) return 'A1';
            if ($num >= 4.5) return 'A2';
            if ($num >= 3.5) return 'B1';
            if ($num >= 2.5) return 'B2';
            if ($num >= 1.5) return 'C1';
            if ($num >= 0.5) return 'C2';
            return '-';
        };
    
        return view('ratings.index', compact('sections', 'gradeFromNumber'));
    }
    
    

    // Show form to create rating
    public function create() {
        $tasks = Task::all(); // select task for rating
        return view('ratings.create', compact('tasks'));
    }

    // Store or update rating
    public function store(Request $request) {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'self_rating' => 'nullable|integer|min:0|max:5',
            'self_comment' => 'nullable|string',
            'assessor_rating' => 'nullable|integer|min:0|max:5',
            'assessor_comment' => 'nullable|string',
            'reviewer_rating' => 'nullable|integer|min:0|max:5',
            'reviewer_comment' => 'nullable|string',
        ]);

        // update if exists, otherwise create
        Rating::updateOrCreate(
            ['task_id' => $request->task_id, 'user_id' => Auth::id()],
            $request->only([
                'self_rating','self_comment',
                'assessor_rating','assessor_comment',
                'reviewer_rating','reviewer_comment'
            ])
        );

        return redirect()->route('ratings.index')
                         ->with('success', 'Rating saved successfully!');
    }

    public function updateSelf(Request $request, \App\Models\Rating $rating)
{
    // Ensure the logged-in user can only update their own self rating
    if($rating->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $request->validate([
        'self_rating' => 'nullable|integer|min:0|max:5',
        'self_comment' => 'nullable|string|max:1000',
    ]);

    $rating->update([
        'self_rating' => $request->self_rating,
        'self_comment' => $request->self_comment,
    ]);

    return response()->json(['success' => true]);
}


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

    return redirect()->back()->with('success', 'Ratings saved successfully!');
}



}
