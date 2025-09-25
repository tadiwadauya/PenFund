@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h3>{{ $user->department }}</h3>
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

    @php
    $status = $approval->status ?? 'Not Submitted';

    $statusClass = match($status) {
        'Not Submitted' => 'bg-warning',
        'Rejected'      => 'bg-danger',
        'Approved'      => 'bg-success',
        'Pending'       => 'bg-primary',
        default         => 'bg-secondary',
    };
@endphp

<div class="{{ $statusClass }} color-palette p-2 rounded">
    <p class="mb-0 text-white">
        Status: {{ $status }}
    </p>

    @if($status === 'Rejected' && !empty($approval->comment))
        <p class="mt-1 text-white">
            <strong>Reason:</strong> {{ $approval->comment }}
        </p>
    @endif
</div>

    <!-- {{-- Purposes --}}

    <h3>Purposes</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            @forelse($purposes as $purpose)
                <tr>
                    <td>{!! $purpose->purpose !!}</td>
                    <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                    <td>
                
                        <a href="{{ route('purposes.edit', $purpose) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('mypurpose.destroy', $purpose->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this purpose?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No purposes found.</td></tr>
            @endforelse
        </tbody>
    </table> -->
</br>
    {{-- KEY TASK --}}
    <a href="{{ route('targets.create') }}" class="btn btn-primary ml-auto">Add New Key Task</a>
    <h3>KEY TASK</h3>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Key Task</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($targets as $target)
            <tr>
                <td>{{ $target->target_name }}</td>
                <td>
                    
                    <a href="{{ route('targets.edit', $target->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('targets.destroy', $target->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this task?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No targets found.</td>
            </tr>
        @endforelse
    </tbody>
</table>


    
    <br>
<hr>
<br>

    {{-- Task --}}
    <a href="{{ route('objectives.create') }}" class="btn btn-primary ml-auto">Add New Objective</a>
    <h3>OBJECTIVES</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Key Tasks</th>
                <th>Objectives</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($objectives as $objective)
                <tr>
                    <td>{{ $objective->target->target_name ?? '-' }}</td>
                    <td>{{ $objective->objective }}</td>
                    <td>
                        <a href="{{ route('objectives.edit', $objective->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this objective?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No objectives found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <br>
<hr>
<br>

    {{-- Initiatives --}}
    <a href="{{ route('initiatives.create') }}" class="btn btn-primary ml-auto">Add Task</a>
    <h3>Task and Targets</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Key Task</th>
                <th>Objective</th>
                <th>Task</th>
                <th><strong>Target</strong></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($initiatives as $initiative)
                <tr>
                    <td>{{ $initiative->target->target_name ?? '-' }}</td>
                    <td>{{ $initiative->objective->objective ?? '-' }}</td>
                    <td><strong>{{ $initiative->initiative }}</strong></td>
                    <td><strong>{{ $initiative->budget }}</strong></td>
                    <td>
                        <a href="{{ route('initiatives.edit', $initiative->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('initiatives.destroy', $initiative->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this initiative?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No Actions found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <hr>
    <h3>Generate Report</h3>
    <form method="POST" action="{{ route('report.generate') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        <div class="form-group">
            <label for="period_id">Select Period:</label>
            <select name="period_id" class="form-control" required>
                @foreach(\App\Models\Period::all() as $period)
                    <option value="{{ $period->id }}">{{ $period->year }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-info">Generate Report</button>
    </form>
<br>
{{-- Show submit if nothing exists --}}
@if(!$approval)
    <form method="POST" action="{{ route('user.performance.submit', $period->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            Submit for Approval
        </button>
    </form>
@endif

{{-- Show resubmit if rejected --}}
@if($approval && $approval->status === 'Rejected')
    <form method="POST" action="{{ route('user.performance.submit', $period->id) }}">
        @csrf
        <button type="submit" class="btn btn-warning">
            Resubmit for Approval
        </button>
    </form>
@endif

</div>
</section>
</div>
</div>
@endsection
