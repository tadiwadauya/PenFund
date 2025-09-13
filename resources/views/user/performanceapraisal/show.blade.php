@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>My Performance Appraisal - Period {{ $period->year }}</h1>
    @php
    $status = $authorisation->status ?? 'Not Submitted';

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

    @if($status === 'Rejected' && !empty($authorisation->comment))
        <p class="mt-1 text-white">
            <strong>Reason:</strong> {{ $authorisation->comment }}
        </p>
    @endif
</div>

    {{-- Purposes --}}

    <h3>Purpose</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Created At</th>
            </tr>
            @forelse($purposes as $purpose)
                <tr>
                    <td>{{ $purpose->purpose }}</td>
                    <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                  
                </tr>
            @empty
                <tr><td colspan="3">No purposes found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <br>
<hr>


    {{-- Initiatives --}}
 <h3>Perfomance Appraisal</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Target</th>
                <th>Objective</th>
                <th>Initiative</th>
                <th>Appraisal Rating </th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($initiatives as $initiative)
                <tr>
                    <td>{{ $initiative->target->target_name ?? '-' }}</td>
                    <td>{{ $initiative->objective->objective ?? '-' }}</td>
                    <td>{{ $initiative->initiative }}</td>
                    <td>{{ $initiative->rating }}</td>
                    <td>{{ $initiative->comment }}</td>
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
@if(!$authorisation)
    <form method="POST" action="{{ route('user.performance.submit', $period->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            Submit for authorisation
        </button>
    </form>
@endif

{{-- Show resubmit if rejected --}}
@if($authorisation && $authorisation->status === 'Rejected')
    <form method="POST" action="{{ route('user.performance.submit', $period->id) }}">
        @csrf
        <button type="submit" class="btn btn-warning">
            Resubmit for authorisation
        </button>
    </form>
@endif

</div>
</section>
</div>
</div>
@endsection
