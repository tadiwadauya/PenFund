@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>Actions To Support Objectives</h1>
            </div>
            <div class="pull-right">
                <a href="{{ route('initiatives.create') }}" class="btn btn-primary ml-auto">Create New Action</a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- User filter --}}
    <form method="GET" action="{{ route('initiatives.index') }}" class="mb-3">
        <div class="form-group">
            <label for="selected_user">Select User:</label>
            <select name="selected_user" id="selected_user" class="form-control" required>
                <option value="">-- Choose a User --</option>
                @foreach ($managedUsers as $mUser)
                    <option value="{{ $mUser->id }}" 
                        {{ request('selected_user') == $mUser->id ? 'selected' : '' }}>
                        {{ $mUser->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">View Actions</button>
    </form>

    @if(request('selected_user'))
        @if ($initiatives->isEmpty())
            <p>No initiatives found for this user.</p>
        @else
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Target</th>
                        <th>Objective</th>
                        <th>Action</th>
                        <th>Budget/Action</th>
                        <th>Employee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($initiatives as $initiative)
                        <tr>
                            <td>{{ $initiative->period->year }}</td>
                            <td>{{ $initiative->target->target_name }}</td>
                            <td>{{ $initiative->objective->objective }}</td>
                            <td>{{ $initiative->initiative }}</td>
                            <td>{{ $initiative->budget }}</td>
                            <td>{{ $initiative->user->name }}</td>
                            <td>
                                <a href="{{ route('initiatives.show', $initiative) }}" class="btn btn-info">View</a>
                                <a href="{{ route('initiatives.edit', $initiative) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('initiatives.destroy', $initiative) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this initiative?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p class="mt-3">Please select a user to view their Actions to support objectives.</p>
    @endif

</div>
</section>
</div>
</div>
@endsection
