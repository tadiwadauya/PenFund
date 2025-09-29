@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tasks</h2>
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Add Task</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Task Name</th>
                <th>Section</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->name }}</td>
                    <td>{{ $task->section->name }}</td>
                    <td>{{ $task->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
