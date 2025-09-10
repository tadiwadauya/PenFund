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
                <h1>Objectives</h1>
            </div>
            <div class="pull-right">
            <a href="{{ url('objectives') }}" class="btn btn-info  ml-auto">Objectives</a>
            <a href="{{ url('purposes') }}" class="btn btn-info ml-auto">Purpose</a>
            <a href="{{ url('report') }}" class="btn btn-info ml-auto">Perfomance Target Report</a>
            </div>
        </div>
    </div>

    {{-- User filter --}}
    <form method="GET" action="{{ route('objectives.index') }}" class="mb-3">
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
        <button type="submit" class="btn btn-success">View Objectives</button>
    </form>

    @if(request('selected_user'))
        @if ($objectives->isEmpty())
            <p>No objectives found for this user.</p>
        @else
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Target</th>
                        <th>Objective</th>
                        <th>User</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($objectives as $objective)
                        <tr>
                            <td>{{ $objective->period->year }}</td>
                            <td>{{ $objective->target->target_name }}</td>
                            <td>{{ $objective->objective }}</td>
                            <td>{{ $objective->user->name }}</td>
                            <td>{{ $objective->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('objectives.show', $objective) }}" class="btn btn-info">View</a>
                                <a href="{{ route('objectives.edit', $objective) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('objectives.destroy', $objective) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this objective?');">
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
        <p class="mt-3">Please select a user to view their objectives.</p>
    @endif

</div>
</section>
</div>
</div>
@endsection
