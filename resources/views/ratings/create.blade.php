@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Rating</h2>
    <form action="{{ route('ratings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Select Task</label>
            <select name="task_id" class="form-control" required>
                <option value="">Select Task</option>
                @foreach($tasks as $task)
                    <option value="{{ $task->id }}">
                        {{ $task->section->name ?? 'No Section' }} → {{ $task->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <h5>Self Rating</h5>
        <div class="mb-3">
            <input type="number" name="self_rating" class="form-control" placeholder="Rating 0-5">
            <textarea name="self_comment" class="form-control mt-2" placeholder="Self Comment"></textarea>
        </div>

        <h5>Assessor Rating</h5>
        <div class="mb-3">
            <input type="number" name="assessor_rating" class="form-control" placeholder="Rating 0-5">
            <textarea name="assessor_comment" class="form-control mt-2" placeholder="Assessor Comment"></textarea>
        </div>

        <h5>Reviewer Rating</h5>
        <div class="mb-3">
            <input type="number" name="reviewer_rating" class="form-control" placeholder="Rating 0-5">
            <textarea name="reviewer_comment" class="form-control mt-2" placeholder="Reviewer Comment"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Rating</button>
    </form>
</div>
@endsection

