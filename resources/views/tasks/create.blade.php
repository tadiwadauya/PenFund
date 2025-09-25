@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Task</h2>
    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <div class="mb-3">
            <label>Period</label>
            <select name="period_id" class="form-control select2"  required>
            @foreach ($periods as $period)
                <option value="{{ $period->id }}">{{ $period->year }}</option>
            @endforeach
        </select>
        </div>

        <div class="mb-3">
            <label>User</label>
            <select name="user_id" class="form-control">
            <option value="{{ auth()->user()->id }}" selected>
                {{ auth()->user()->name }}
            </option>
            </select>
        </div>

        <div class="mb-3">
            <label>Key Task</label>
            <input type="text" name="key_task" class="form-control">
        </div>

        <div class="mb-3">
            <label>Objective</label>
            <textarea name="objective" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Task</label>
            <input type="text" name="task" class="form-control">
        </div>

        <div class="mb-3">
            <label>Target</label>
            <input type="text" name="target" class="form-control">
        </div>

        

        <button class="btn btn-success">Save Task</button>
    </form>
</div>
@endsection
