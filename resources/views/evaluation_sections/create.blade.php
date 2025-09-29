@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Section</h2>
    <form action="{{ route('evaluation_sections.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Section Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Section</button>
    </form>
</div>
@endsection
