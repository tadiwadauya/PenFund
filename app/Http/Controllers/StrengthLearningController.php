<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserStrength;
use App\Models\UserLearningArea;

class StrengthLearningController extends Controller
{
    /**
     * Show Strengths & Learning Areas page
     */
    public function index()
    {
        $user = Auth::user();

        // Self perception
        $selfStrengths = UserStrength::where('user_id', $user->id)
            ->where('type','self')->get();
        $selfLearning  = UserLearningArea::where('user_id', $user->id)
            ->where('type','self')->get();

        // Assessor perception
        $assessorStrengths = UserStrength::where('user_id', $user->id)
            ->where('type','assessor')->get();
        $assessorLearning  = UserLearningArea::where('user_id', $user->id)
            ->where('type','assessor')->get();

        return view('user.strengths_learning.index', compact(
            'selfStrengths', 'selfLearning', 'assessorStrengths', 'assessorLearning'
        ));
    }

    /**
     * Store self-perceived strength
     */
    public function storeStrength(Request $request)
    {
        $request->validate(['strength' => 'required|string|max:255']);
        UserStrength::create([
            'user_id' => Auth::id(),
            'strength' => $request->strength,
            'type' => 'self'
        ]);
        return redirect()->back()->with('success', 'Strength added!');
    }

    /**
     * Store self-perceived learning area
     */
    public function storeLearning(Request $request)
    {
        $request->validate(['learning_area' => 'required|string|max:255']);
        UserLearningArea::create([
            'user_id' => Auth::id(),
            'learning_area' => $request->learning_area,
            'type' => 'self'
        ]);
        return redirect()->back()->with('success', 'Learning area added!');
    }

    /**
     * Assessor adds strength for user
     */
    public function storeAssessorStrength(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'strength' => 'required|string|max:255'
        ]);
        UserStrength::create([
            'user_id' => $request->user_id,
            'strength' => $request->strength,
            'type' => 'assessor'
        ]);
        return redirect()->back()->with('success', 'Assessor strength added!');
    }

    /**
     * Assessor adds learning area for user
     */
    public function storeAssessorLearning(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'learning_area' => 'required|string|max:255'
        ]);
        UserLearningArea::create([
            'user_id' => $request->user_id,
            'learning_area' => $request->learning_area,
            'type' => 'assessor'
        ]);
        return redirect()->back()->with('success', 'Assessor learning added!');
    }

    /**
     * Update existing strength (self or assessor)
     */
    public function updateStrength(Request $request, $id)
    {
        $request->validate(['strength' => 'required|string|max:1000']);
        $strength = UserStrength::findOrFail($id);
        $strength->update([
            'strength' => $request->strength
        ]);
        return redirect()->back()->with('success', 'Strength updated!');
    }

    /**
     * Update existing learning area (self or assessor)
     */
    public function updateLearning(Request $request, $id)
    {
        $request->validate(['learning_area' => 'required|string|max:1000']);
        $area = UserLearningArea::findOrFail($id);
        $area->update([
            'learning_area' => $request->learning_area
        ]);
        return redirect()->back()->with('success', 'Learning area updated!');
    }

// Update Assessor Strength
public function updateAssessorStrength(Request $request, $id)
{
    $request->validate([
        'strength' => 'required|string|max:255'
    ]);

    $strength = UserStrength::where('type','assessor')->findOrFail($id);
    $strength->update(['strength' => $request->strength]);

    return redirect()->back()->with('success','Assessor strength updated!');
}

// Update Assessor Learning Area
public function updateAssessorLearning(Request $request, $id)
{
    $request->validate([
        'learning_area' => 'required|string|max:255'
    ]);

    $learning = UserLearningArea::where('type','assessor')->findOrFail($id);
    $learning->update(['learning_area' => $request->learning_area]);

    return redirect()->back()->with('success','Assessor learning area updated!');
}


    /**
     * Delete strength
     */
    public function destroyStrength($id)
    {
        UserStrength::findOrFail($id)->delete();
        return redirect()->back()->with('success','Strength deleted!');
    }

    /**
     * Delete learning area
     */
    public function destroyLearning($id)
    {
        UserLearningArea::findOrFail($id)->delete();
        return redirect()->back()->with('success','Learning area deleted!');
    }
}
