<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::all();
        return view('periods.index', compact('periods'));
    }

    // Show the form for creating a new period
    public function create()
    {
        return view('periods.create');
    }

    // Store a newly created period in storage
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:4|unique:periods,year',
        ]);

        Period::create($request->all());

        return redirect()->route('periods.index')->with('success', 'Period created successfully.');
    }

    // Display the specified period
    public function show(Period $period)
    {
        return view('periods.show', compact('period'));
    }

    // Show the form for editing the specified period
    public function edit(Period $period)
    {
        return view('periods.edit', compact('period'));
    }

    // Update the specified period in storage
    public function update(Request $request, Period $period)
    {
        $request->validate([
            'year' => 'required|string|max:4|unique:periods,year,' . $period->id,
        ]);

        $period->update($request->all());

        return redirect()->route('periods.index')->with('success', 'Period updated successfully.');
    }

    // Remove the specified period from storage
    public function destroy(Period $period)
    {
        $period->delete();

        return redirect()->route('periods.index')->with('success', 'Period deleted successfully.');
    }
}
