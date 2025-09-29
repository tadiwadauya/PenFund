@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Sections</h2>
    <a href="{{ route('evaluation_sections.create') }}" class="btn btn-success mb-3">Add Section</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Section Name</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
                <tr>
                    <td>{{ $section->id }}</td>
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
