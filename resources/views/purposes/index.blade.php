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
                <h1>Purpose</h1>
            </div>
            <div class="pull-right">
    <a href="{{ url('objectives') }}" class="btn btn-info  ml-auto">Objectives</a>
    <a href="{{ url('purposes') }}" class="btn btn-info ml-auto">Purpose</a>
    <a href="{{ url('report') }}" class="btn btn-info ml-auto">Perfomance Target Report</a>
              
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- User filter --}}
    <form method="GET" action="{{ route('purposes.index') }}" class="mb-3">
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
        <button type="submit" class="btn btn-success">View Purposes</button>
    </form>

    @if(request('selected_user'))
        @if ($purposes->isEmpty())
            <p>No purposes found for this user.</p>
        @else
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Purpose</th>
                        <th>Period</th>
                        <th>User</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purposes as $purpose)
                        <tr>
                            <td>{{ $purpose->purpose }}</td>
                            <td>{{ $purpose->period->year }}</td>
                            <td>{{ $purpose->user->name }}</td>
                            <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('purposes.show', $purpose) }}" class="btn btn-info">View</a>
                                <a href="{{ route('purposes.mypurpose.edit', $purpose->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('purposes.destroy', $purpose) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this purpose?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p class="mt-3">Please select a user to view their purposes.</p>
    @endif

</div>
</section>
</div>
</div>
@endsection
