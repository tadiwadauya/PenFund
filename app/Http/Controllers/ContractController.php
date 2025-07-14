<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use App\Models\Initiative;
use App\Models\Objective;
use App\Models\Period;
use App\Models\User; // Import the User model
use App\Models\Performance;
use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    public function mypurpose()
    {
        $purposes = Purpose::with('period')->where('user_id', auth()->id())->get();
        
          // Return the index view with the purposes
          return view('purposes.mypurpose', compact('purposes'));
    }

    public function myobjective()
    {
        $objectives = Objective::with('period','target')->where('user_id', auth()->id())->get();
        
          // Return the index view with the purposes
          return view('objectives.myobjective', compact('objectives'));
    }

    public function myinitiative()
    {
        $initiatives = Initiative::with('period','target')->where('user_id', auth()->id())->get();
        
          // Return the index view with the purposes
          return view('initiatives.myinitiative', compact('initiatives'));
    }

    public function create()
    {
        $periods = Period::all();
        $users = User::all();
        return view('contracts.create', compact('periods', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'purpose' => 'required',
            // Other validations...
        ]);

        Contract::create($request->all());
        return redirect()->route('contracts.index');
    }

    // Implement other methods (show, edit, update, destroy)

}
