@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tasks</h2>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add Task</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Key Task</th>
                <th>Objective</th>
                <th>Task</th>
                <th>Target</th>
                <th>Self Rating</th>
                <th>Self Comment</th>
                <th>Assessor Rating</th>
                <th>Assessor Comment</th>
                <th>Reviewer Rating</th>
                <th>Reviewer Comment</th>
                <th>User</th>
                <th>Period</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->key_task }}</td>
                <td>{{ $task->objective }}</td>
                <td>{{ $task->task }}</td>
                <td>{{ $task->target }}</td>
                <td>{{ $task->self_rating }}</td>
                <td>{{ $task->self_comment }}</td>
                <td>{{ $task->assessor_rating }}</td>
                <td>{{ $task->assessor_comment }}</td>
                <td>{{ $task->reviewer_rating }}</td>
                <td>{{ $task->reviewer_comment }}</td>
                <td>{{ $task->user->name }}</td>
                <td>{{ $task->period->name }}</td>
                <td>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this task?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
