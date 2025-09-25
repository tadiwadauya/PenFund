@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Task</h2>
    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Period</label>
            <select name="period_id" class="form-control">
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ $task->period_id == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>User</label>
            <select name="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Key Task</label>
            <input type="text" name="key_task" class="form-control" value="{{ $task->key_task }}">
        </div>

        <div class="mb-3">
            <label>Objective</label>
            <textarea name="objective" class="form-control">{{ $task->objective }}</textarea>
        </div>

        <div class="mb-3">
            <label>Task</label>
            <input type="text" name="task" class="form-control" value="{{ $task->task }}">
        </div>

        <div class="mb-3">
            <label>Target</label>
            <input type="text" name="target" class="form-control" value="{{ $task->target }}">
        </div>

        <div class="mb-3">
            <label>Self Rating</label>
            <input type="number" name="self_rating" min="1" max="5" class="form-control" value="{{ $task->self_rating }}">
        </div>

        <div class="mb-3">
            <label>Self Comment</label>
            <textarea name="self_comment" class="form-control">{{ $task->self_comment }}</textarea>
        </div>

        <div class="mb-3">
            <label>Assessor Rating</label>
            <input type="number" name="assessor_rating" min="1" max="5" class="form-control" value="{{ $task->assessor_rating }}">
        </div>

        <div class="mb-3">
            <label>Assessor Comment</label>
            <textarea name="assessor_comment" class="form-control">{{ $task->assessor_comment }}</textarea>
        </div>

        <div class="mb-3">
            <label>Reviewer Rating</label>
            <input type="number" name="reviewer_rating" min="1" max="5" class="form-control" value="{{ $task->reviewer_rating }}">
        </div>

        <div class="mb-3">
            <label>Reviewer Comment</label>
            <textarea name="reviewer_comment" class="form-control">{{ $task->reviewer_comment }}</textarea>
        </div>

        <button class="btn btn-success">Update Task</button>
    </form>
</div>
@endsection
