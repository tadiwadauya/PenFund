<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPurposeController extends Controller
{
    public function index()
    {
        // Fetch all purposes for the authenticated user
        $purposes = Purpose::with('period')->where('user_id', Auth::id())->get();
        
        // Return the index view with the user's purposes
        return view('purposes.mypurpose.index', compact('purposes'));
    }

    public function create()
    {
        // Fetch all periods to show in the create form
        $periods = Period::all();
        return view('purposes.mypurpose.create', compact('periods'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'period_id' => 'required|exists:periods,id',
        ]);
        
        // Check if the purpose already exists for the authenticated user and the specified period
        $existingPurpose = Purpose::where('user_id', Auth::id())
            ->where('period_id', $request->period_id)
            ->first();
        
        if ($existingPurpose) {
            return redirect()->back()->withErrors(['period_id' => 'You have already created a purpose for this period.'])->withInput();
        }
    
        // Create a new purpose with the authenticated user's ID
        Purpose::create([
            'user_id' => Auth::id(), // Set the user_id to the authenticated user's ID
            'period_id' => $request->period_id,
        ]);
        
        // Redirect to the index route for the user's purposes
        return redirect()->route('user.performance.index')->with('success', 'Purpose created successfully.');
    }

    

    public function show(Purpose $purpose)
    {
        // Ensure the purpose belongs to the authenticated user
        if ($purpose->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        return view('purposes.mypurpose.show', compact('purpose'));
    }

    public function edit(Purpose $purpose)
    {
        // Fetch all periods to show in the edit form
        $periods = Period::all();
        return view('purposes.mypurpose.edit', compact('purpose', 'periods'));
    }



    public function update(Request $request, Purpose $purpose)
    {
        // Validate the incoming request
        $request->validate([
            'purpose' => 'required|string|max:255',
            'period_id' => 'required|exists:periods,id',
        ]);
    
        // Check if the period is already used by the user for another purpose (optional)
        $existingPurpose = Purpose::where('user_id', Auth::id())
            ->where('period_id', $request->period_id)
            ->where('id', '!=', $purpose->id) // Exclude the current purpose being updated
            ->first();
    
        if ($existingPurpose) {
            return redirect()->back()->withErrors(['period_id' => 'You have already created a purpose for this period.'])->withInput();
        }
    
        // Update the purpose with the new data
        $purpose->update($request->all());
    
        return redirect()->route('mypurpose.index')->with('success', 'Purpose updated successfully.');
    }

    public function destroy(Purpose $purpose)
    {
        // Ensure the purpose belongs to the authenticated user
        if ($purpose->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to delete this purpose.'); // Forbidden
        }
    
        // Delete the purpose
        $purpose->delete();
    
        return redirect()->route('mypurpose.index')->with('success', 'Purpose deleted successfully.');
    }
}