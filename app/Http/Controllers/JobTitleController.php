<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Models\JobTitle;
use App\Models\Section;
use App\Models\User;

class JobTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $jobtitles = JobTitle::latest()->paginate(5);
        $sections = Section::all()->keyBy('id');
        $users = User::all()->keyBy('id');
    
        return view('jobtitles.index', compact('jobtitles', 'users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $departments = Department::all();
        $sections = Section::all();
    
        $data = [
            'departments' => $departments,
            'sections' => $sections,
        ];
    
        return view('jobtitles.create')->with($data);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'jobtitle' => 'required|max:255',
            'department' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
        ], [
            'jobtitle.required' => 'We need a Job Title.',
            'department.nullable' => 'We obviously need a name for a department.',
            'section.nullable' => 'We obviously need a name for a section.', // Corrected the error message
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $jobtitle = JobTitle::create([
            'jobtitle' => $request->input('jobtitle'),
            'department' => $request->input('department'),
            'section' => $request->input('section'),
        ]);
    
        // No need to call $jobtitle->save(); since create() already saves the model
    
        return redirect('jobtitles')->with('success', 'Successfully added Job Title.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jobtitle = JobTitle::findorFail($id);

        return view('jobtitles.show',compact('jobtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $jobtitle = JobTitle::findOrFail($id);
        $departments = Department::all();
        $sections = Section::all(); // Changed variable name to plural for consistency
    
        return view('jobtitles.edit', [
            'jobtitle' => $jobtitle,
            'departments' => $departments, // Ensure the key matches the variable name
            'sections' => $sections, // Ensure the key matches the variable name
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $jobtitle = JobTitle::findorFail($id);

        $validator = Validator::make($request->all(),
            [
                'jobtitle'             => 'required|max:255',
                'department'           => 'nullable|max:255',
                'section'           => 'nullable|max:255',
            ],
            [
                'jobtitle.required'         => 'We need a Job Title.',
                'department.nullable'       => 'We obviously need a name for a department.',
                'section.nullable'       => 'We obviously need a name for a department.',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $jobtitle->jobtitle = $request->input('jobtitle');
        $jobtitle->department = $request->input('department');
        $jobtitle->section = $request->input('section');

        $jobtitle->save();

        return  redirect('jobtitles')->with('success', 'Successfully updated job title.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobTitle $jobtitle)
    {
        $jobtitle->delete();
    
        return redirect()->route('jobtitles.index')
                        ->with('success','jobtitle deleted successfully');
    }
}