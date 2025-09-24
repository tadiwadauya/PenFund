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
                <td>{!! $purpose->purpose !!}</td>
                    <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                  
                </tr>
            @empty
                <tr><td colspan="3">No purposes found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <br>
<hr>
@if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Initiatives --}}
 <h3>Perfomance Appraisal</h3>
 <table class="table table-bordered">
    <thead>
        <tr>
            <th>Target</th>
            <th>Objective</th>
            <th>Initiative</th>
            <th>Target/ Budget</th>
            <th>Actual/Achieved </th>
            <th>Appraisal Rating</th>
            <th>Comment</th>
            <th>Update</th>
        </tr>
    </thead>
    <tbody>
        @forelse($initiatives as $initiative)
            <tr>
                <td>{{ $initiative->target->target_name ?? '-' }}</td>
                <td>{{ $initiative->objective->objective ?? '-' }}</td>
                <td>{{ $initiative->initiative }}</td>
                <td>{{ $initiative->budget }}</td>

                {{-- Inline update form --}}
                
                    <form action="{{ route('initiatives.updateInline', $initiative->id) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        @method('PATCH')
                        <td>
                        {{-- Archived toggle --}}
                        <select name="archieved" class="form-control me-2" style="width: 120px;">
                            <option value="0" {{ $initiative->archieved == 0 ? 'selected' : '' }}>Not Archived</option>
                            <option value="1" {{ $initiative->archieved == 1 ? 'selected' : '' }}>Archived</option>
                        </select>
</td>
<td>
                        {{-- Rating dropdown --}}
                        <select name="rating" class="form-control me-2" style="width: 180px;">
                            <option value="">-- Select Rating --</option>
                            <option value="6" {{ $initiative->rating == 6 ? 'selected' : '' }}>A1 - Outstanding performance. High levels of expertise</option>
                            <option value="5" {{ $initiative->rating == 5 ? 'selected' : '' }}>A2 - Consistently exceeds requirements</option>
                            <option value="4" {{ $initiative->rating == 4 ? 'selected' : '' }}>B1 - Meets requirements. Occasionally exceeds them</option>
                            <option value="3" {{ $initiative->rating == 3 ? 'selected' : '' }}>B2 - Meets requirements</option>
                            <option value="2" {{ $initiative->rating == 2 ? 'selected' : '' }}>C1 - Partially meets requirements. Improvement required</option>
                            <option value="1" {{ $initiative->rating == 1 ? 'selected' : '' }}>C2 - Unacceptable. Well below standard required</option>
                        </select>
</td>
<td>
                        {{-- Comment --}}
                        <input type="text" 
                               name="comment" 
                               value="{{ $initiative->comment }}" 
                               class="form-control me-2" 
                               style="width: 200px;">
</td>
<td>
                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No Actions found.</td></tr>
        @endforelse
    </tbody>
</table>
@php
    // Group initiatives by target_id and calculate averages per target
    $averages = $initiatives
        ->groupBy('target_id')
        ->map(function($group) {
            return [
                'target_name' => $group->first()->target->target_name ?? 'N/A',
                'average' => $group->avg('rating'),
            ];
        });

    // Calculate overall average across all initiatives
    $overallAverage = $initiatives->avg('rating');

    // Map rating number back to label
    function mapRating($value) {
        return match(true) {
            $value >= 5.5 => 'A1',
            $value >= 4.5 => 'A2',
            $value >= 3.5 => 'B1',
            $value >= 2.5 => 'B2',
            $value >= 1.5 => 'C1',
            $value > 0    => 'C2',
            default       => 'No Rating',
        };
    }
@endphp

@if($averages->count())
    <h4 class="mt-4">Average Ratings by Target</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Target</th>
                <th>Performance Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($averages as $avg)
                <tr>
                    <td>{{ $avg['target_name'] }}</td>
                    <td>{{ mapRating($avg['average']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if($overallAverage)
    <h4 class="mt-4">Overall Performance Rating</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Overall Rating</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ mapRating($overallAverage) }}</td>
            </tr>
        </tbody>
    </table>
@endif




    <hr>
    <hr>
    <h3>Generate Report</h3>
    <form method="POST" action="{{ route('appraisalreport.apgenerate') }}">
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
    <form method="POST" action="{{ route('user.performanceapraisal.submit', $period->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            Submit for authorisation
        </button>
    </form>
@endif

{{-- Show resubmit if rejected --}}
@if($authorisation && $authorisation->status === 'Rejected')
    <form method="POST" action="{{ route('user.performanceapraisal.submit', $period->id) }}">
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
