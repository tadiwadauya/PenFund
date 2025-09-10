@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>Manager Dashboard</h1>

    {{-- User Table --}}
    <h3>Managed Users</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Section</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($managedUsers as $mUser)
                <tr>
                    <td>{{ $mUser->name }}</td>
                    <td>{{ $mUser->department }}</td>
                    <td>{{ $mUser->section }}</td>
                    <td>
                        <a href="{{ route('manager.dashboard', ['selected_user' => $mUser->id]) }}" 
                           class="btn btn-info">
                           View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Show details if a user is selected --}}
    @if ($selectedUser)
        <hr>
        <h2>Performance Data for {{ $selectedUser->name }}</h2>

        {{-- Purposes --}}
        <h4>Purposes</h4>
        @if($purposes->isEmpty())
            <p>No purposes found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Purpose</th>
                        <th>Period</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purposes as $purpose)
                        <tr>
                            <td>{{ $purpose->purpose }}</td>
                            <td>{{ $purpose->period->year ?? '-' }}</td>
                            <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Objectives --}}
        <h4>Objectives</h4>
        @if($objectives->isEmpty())
            <p>No objectives found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Objective</th>
                        <th>Target</th>
                        <th>Period</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($objectives as $objective)
                        <tr>
                            <td>{{ $objective->objective }}</td>
                            <td>{{ $objective->target->name ?? '-' }}</td>
                            <td>{{ $objective->period->year ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Initiatives --}}
        <h4>Initiatives</h4>
        @if($initiatives->isEmpty())
            <p>No initiatives found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Initiative</th>
                        <th>Objective</th>
                        <th>Target</th>
                        <th>Period</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($initiatives as $initiative)
                        <tr>
                            <td>{{ $initiative->initiative }}</td>
                            <td>{{ $initiative->objective->objective ?? '-' }}</td>
                            <td>{{ $initiative->target->name ?? '-' }}</td>
                            <td>{{ $initiative->period->year ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Generate Report --}}
        <form method="POST" action="{{ route('report.generate') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
            <div class="form-group">
                <label for="period_id">Select Period:</label>
                <select name="period_id" class="form-control" required>
                    @foreach(\App\Models\Period::all() as $period)
                        <option value="{{ $period->id }}">{{ $period->year }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
    @endif

</div>
</section>
</div>
</div>
@endsection
