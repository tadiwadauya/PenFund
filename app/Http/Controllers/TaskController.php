<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Period;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['user','period'])->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $periods = Period::all();
        $users = User::all();
        return view('tasks.create', compact('periods','users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_id'        => 'required|exists:periods,id',
            'user_id'          => 'required|exists:users,id',
            'key_task'         => 'required|string|max:255',
            'task'             => 'required|string|max:255',
            'target'           => 'nullable|string|max:255',
            'objective'        => 'nullable|string',
            'self_rating'      => 'nullable|integer|min:1|max:5',
            'self_comment'     => 'nullable|string',
            'assessor_rating'  => 'nullable|integer|min:1|max:5',
            'assessor_comment' => 'nullable|string',
            'reviewer_rating'  => 'nullable|integer|min:1|max:5',
            'reviewer_comment' => 'nullable|string',
        ]);

        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $periods = Period::all();
        $users = User::all();
        return view('tasks.edit', compact('task','periods','users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'period_id'        => 'required|exists:periods,id',
            'user_id'          => 'required|exists:users,id',
            'key_task'         => 'required|string|max:255',
            'task'             => 'required|string|max:255',
            'target'           => 'nullable|string|max:255',
            'objective'        => 'nullable|string',
            'self_rating'      => 'nullable|integer|min:1|max:5',
            'self_comment'     => 'nullable|string',
            'assessor_rating'  => 'nullable|integer|min:1|max:5',
            'assessor_comment' => 'nullable|string',
            'reviewer_rating'  => 'nullable|integer|min:1|max:5',
            'reviewer_comment' => 'nullable|string',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
