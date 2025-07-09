<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::all();
        return view('contracts.index', compact('contracts'));
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
