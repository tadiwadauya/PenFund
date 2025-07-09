<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use App\Models\Period;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurposeController extends Controller
{
    public function index()
    {
        // Fetch all purposes with their associated periods and users
        $purposes = Purpose::with('period', 'user')->get();
        
        // Return the index view with the purposes
        return view('purposes.index', compact('purposes'));
    }

    public function create()
    {
        // Fetch all periods and users to show in the create form
        $periods = Period::all();
        $users = User::all(); // Fetch all users
        return view('purposes.create', compact('periods', 'users'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validate the user_id
            'purpose' => 'required|string|max:255',
            'period_id' => 'required|exists:periods,id',
        ]);

        // Create a new purpose with the selected user ID
        Purpose::create([
            'user_id' => $request->user_id, // Use the selected user ID
            'purpose' => $request->purpose,
            'period_id' => $request->period_id,
        ]);

        return redirect()->route('purposes.index')->with('success', 'Purpose created successfully.');
    }

    public function show(Purpose $purpose)
    {
        // Show a specific purpose along with the associated user and period
        return view('purposes.show', compact('purpose'));
    }

    public function edit(Purpose $purpose)
    {
        // Fetch all periods and users to show in the edit form
        $periods = Period::all(); // Fetch all periods
        $users = User::all(); // Fetch all users
        return view('purposes.edit', compact('purpose', 'periods', 'users'));
    }

    public function update(Request $request, Purpose $purpose)
{
    // Validate the incoming request
    $request->validate([
        'user_id' => 'required|exists:users,id', // Validate the user_id
        'purpose' => 'required|string|max:255',
        'period_id' => 'required|exists:periods,id',
    ]);

    // Update the purpose with the new data
    $purpose->update($request->all());

    return redirect()->route('purposes.index')->with('success', 'Purpose updated successfully.');
}
    public function destroy(Purpose $purpose)
    {
        // Delete the purpose
        $purpose->delete();

        return redirect()->route('purposes.index')->with('success', 'Purpose deleted successfully.');
    }
}


