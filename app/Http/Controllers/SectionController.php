<?php

namespace App\Http\Controllers;
use App\Models\Section;
use App\Models\Department;
use App\Models\User;

use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::latest()->paginate(5);
        $users = User::all()->keyBy('id'); 
        $departments = Department::all()->keyBy('id');
    
        return view('sections.index', compact('sections', 'users','departments'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        $users = User::all();
        $departments = Department::all();
    
        $data = [
            'users' => $users,
            'departments' => $departments, // Corrected key
        ];
        return view('sections.create')->with($data);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'section' => 'required|max:255|unique:sections',
            'department' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'asst_manager' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
        ]);
    
        // Uncomment the line below if you want to debug the validated data
        // dd($validatedData);
    
        Section::create($validatedData); // Use $validatedData instead of $request->all()
        
        return redirect()->route('sections.index')
                         ->with('success', 'Section created successfully.');
    }
    

    public function show(Section $section)
    {
        $users = User::all()->keyBy('id');
        $departments = Department::all()->keyBy('id');
        return view('sections.show',compact('section','users','departments'));
    }
    

    public function edit(Section $section)
    {
        $users = User::all()->keyBy('id');
        $departments = Department::all()->keyBy('id');
        return view('sections.edit',compact('section','users','departments'));
    }
    

    public function update(Request $request, Section $section)
    {
         request()->validate([
            'section' => 'required',
            'department' => 'required',
            'manager' => 'required',
            'asst_manager' => 'nullable',
        ]);
    
        $section->update($request->all());
    
        return redirect()->route('sections.index')
                        ->with('success','section updated successfully');
    }
    

    public function destroy(Section $section)
    {
        $section->delete();
    
        return redirect()->route('sections.index')
                        ->with('success','Section deleted successfully');
    }
}