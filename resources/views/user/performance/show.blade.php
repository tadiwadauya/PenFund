@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>My Performance Target - Period {{ $period->year }}</h1>
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

    {{-- Purposes --}}

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
                    <td>{{ $purpose->purpose }}</td>
                    <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('mypurpose.show', $purpose->id) }}" class="btn btn-sm btn-info">Show</a>
                        <a href="{{ route('mypurpose.edit', $purpose->id) }}" class="btn btn-sm btn-warning">Edit</a>
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
    </table>
    <br>
<hr>
<br>

    {{-- Objectives --}}
    <a href="{{ route('objectives.create') }}" class="btn btn-primary ml-auto">Add New Objective</a>
    <h3>Objectives</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Objective</th>
                <th>Target</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($objectives as $objective)
                <tr>
                    <td>{{ $objective->objective }}</td>
                    <td>{{ $objective->target->target_name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('objectives.show', $objective->id) }}" class="btn btn-sm btn-info">Show</a>
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
    <a href="{{ route('initiatives.create') }}" class="btn btn-primary ml-auto">Add Action to Support Objectives</a>
    <h3>Actions To Support Objectives</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Initiative</th>
                <th>Objective</th>
                <th>Target</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($initiatives as $initiative)
                <tr>
                    <td>{{ $initiative->initiative }}</td>
                    <td>{{ $initiative->objective->objective ?? '-' }}</td>
                    <td>{{ $initiative->target->target_name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('initiatives.show', $initiative->id) }}" class="btn btn-sm btn-info">Show</a>
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
