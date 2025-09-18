@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h1>Users Pending Approval of Performance Data</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    
    @if($approvals->isEmpty())
        <p>No users pending approval.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr><th>User</th><th>Department</th><th>Section</th><th>Period</th><th>Action</th></tr>
            </thead>
            <tbody>
            @foreach($approvals as $approval)
                <tr>
                    <td>{{ $approval->user->first_name }} {{ $approval->user->last_name }}</td>
                    <td>{{ $approval->user->department }}</td>
                    <td>{{ $approval->user->section }}</td>
                    <td>{{ $approval->period->year }}</td>
                    <td>
                        <a href="{{ route('manager.approvals.show', $approval->id) }}" class="btn btn-info">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
