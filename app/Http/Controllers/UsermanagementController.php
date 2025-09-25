<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Section;
use App\Models\JobTitle;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UsermanagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $jobtitles = JobTitle::all();      
        $sections = Section::all();
        $users = User::all();
        return view('users.create',compact('departments','jobtitles','sections','users'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:15', 
            'extension' => 'nullable|string|max:4', 
            'jobtitle' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'supervisor_id' => 'nullable|string|max:255',
            'reviewer_id' => 'nullable|string|max:10',
            'gender' => 'nullable|string|max:10', // Adjust max length as needed
            'dob' => 'nullable|date', // Ensure dob is a valid date            
            'is_admin' => 'required|integer|max:2', // Assuming roles is an array
            'password' => 'same:confirm-password', // Use 'confirmed' for 'confirm-password'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
    
        return redirect()->route('users.index')
                         ->with('success', 'User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $jobtitles = JobTitle::all();
        $sections = Section::all();
        $users = User::all(); // supervisors
    
        return view('users.edit',compact('user','departments','jobtitles','sections','users'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Base validation (no password here)
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'jobtitle' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:15',
            'section' => 'nullable|string|max:10',
            'speeddial' => 'nullable|string|max:10',
            'gender' => 'nullable|string|max:10',
            'dob' => 'nullable|date',
            'supervisor_id' => 'nullable|string|max:10',
            'reviewer_id' => 'nullable|string|max:10',
            'grade' => 'nullable|string|max:10',
        ]);
    
        $input = $request->all();
    
        // ✅ Only validate password if user typed one
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|same:confirm-password',
            ]);
            $input['password'] = Hash::make($input['password']);
        } else {
            // remove both password fields so they don’t interfere
            $input = Arr::except($input, ['password', 'confirm-password']);
        }
    
        $user = User::findOrFail($id);
        $user->update($input);
    
        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully');
    }
    
    

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}
