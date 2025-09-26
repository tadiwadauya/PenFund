@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">
<a href="{{ url()->previous() }}" class="btn btn-primary mb-2">Back</a>
    <h3> {{ $user->jobtitle }} PERFORMANCE TARGETS FOR THE PERIOD JAN – DEC {{ $period->year }}</h3>

    {{-- ================== USER DETAILS ================== --}}
    <table class="table table-bordered mb-4">
    <tr>
        <!-- Employee Details (Left) -->
        <td width="50%">
            <p><strong>Name of Staff Member Being Assessed:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Department:</strong> {{ $user->department }}</p>
            <p><strong>Section:</strong> {{ $user->section }}</p>
            <p><strong>Job Title:</strong> {{ $user->jobtitle }}</p>
            <p><strong>Grade:</strong> {{ $user->grade }}</p>
        </td>

        <!-- Supervisor Details (Right) -->
        <td width="50%">
            <p><strong>Assessor:</strong> {{ $user->supervisor ? $user->supervisor->first_name . ' ' . $user->supervisor->last_name : 'N/A' }}</p>
            <p><strong>Reviewer:</strong> {{ $user->reviewer ? $user->reviewer->first_name . ' ' . $user->reviewer->last_name : 'N/A' }}</p>
            <p><strong>Review Period:</strong> From 01 January {{ $period->year }} to  December {{ $period->year }}</p>
            <!-- <p><strong>Superior Department:</strong> {{ $user->supervisor ? $user->supervisor->department : 'N/A' }}</p>
            <p><strong>Superior Section:</strong> {{ $user->supervisor ? $user->supervisor->section : 'N/A' }}</p> -->
        </td>
    </tr>
</table>


    {{-- targets --}}
    <h3>Task and Targets</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Key Task</th>
                <th>Objective</th>
                <th>Task</th>
                <th>Target</th>
            </tr>
        </thead>
        <tbody>
            @forelse($initiatives as $initiative)
                <tr>
                    <td>{{ $initiative->target->target_name ?? '-' }}</td>
                    <td>{{ $initiative->objective->objective ?? '-' }}</td>
                    <td>{{ $initiative->initiative }}</td>
                    <td>{{ $initiative->budget }}</td>
                    
                </tr>
            @empty
                <tr><td colspan="5">No Task and Targets found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Generate Report --}}
    <h3>Generate Report</h3>
    <form method="POST" action="{{ route('report.generate') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
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
    {{-- Approval Actions --}}
<hr>

    </div>
  </div>
</div>
</div>
</section>
</div>
</div>
@endsection
