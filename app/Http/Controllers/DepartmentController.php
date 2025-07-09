<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::latest()->paginate(5);
        $users = User::all()->keyBy('id'); // Get users and key by paynumber
    
        return view('departments.index', compact('departments', 'users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function create()
    {
        $users = User::all();

        $data = [
            'users' => $users,
        ];
        return view('departments.create')->with($data);
        
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'department' => 'required|max:255|unique:departments',
        'manager' => 'nullable|string|max:255', // Moved 'string' inside the rules
        'asst_manager' => 'nullable|string|max:255', // Moved 'string' inside the rules
    ]);

    // Dump the validated data to check what is being saved
 
    
    Department::create($validatedData);
    
    return redirect()->route('departments.index')
                     ->with('success', 'Department created successfully.');
}
    

    public function show(Department $department)
    {
        $users = User::all()->keyBy('id');
        return view('departments.show',compact('department','users'));
    }
    

    public function edit(Department $department)
    {
        $users = User::all()->keyBy('id');
        return view('departments.edit',compact('department','users'));
    }
    

    public function update(Request $request, Department $department)
    {
         request()->validate([
            'department' => 'required',
            'manager' => 'required',
            'asst_manager' => 'nullable|string|max:255', // Moved 'string' inside the rules
        ]);
    
        $department->update($request->all());
    
        return redirect()->route('departments.index')
                        ->with('success','Department updated successfully');
    }
    

    public function destroy(Department $department)
    {
        $department->delete();
    
        return redirect()->route('departments.index')
                        ->with('success','Department deleted successfully');
    }
}