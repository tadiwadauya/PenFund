@extends('layouts.app')

@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>Performance Assessment for {{ $user->first_name }} {{ $user->last_name }} - Period {{ $period->year }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- STAFF DETAILS --}}
    <table class="table table-bordered mb-4">
        <tr class="table-secondary">
            <td width="50%">
                <table class="table table-sm mb-0">
                    <tr><th>Name of Staff:</th><td>{{ $user->first_name }} {{ $user->last_name }}</td></tr>
                    <tr><th>Department:</th><td>{{ $user->department }}</td></tr>
                    <tr><th>Section:</th><td>{{ $user->section }}</td></tr>
                    <tr><th>Job Title:</th><td>{{ $user->jobtitle }}</td></tr>
                    <tr><th>Grade:</th><td>{{ $user->grade }}</td></tr>
                </table>
            </td>
            <td width="50%">
                <table class="table table-sm mb-0">
                    <tr><th>Assessor:</th><td>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</td></tr>
                    <tr><th>Reviewer:</th><td>{{ $user->reviewer ? $user->reviewer->first_name . ' ' . $user->reviewer->last_name : 'N/A' }}</td></tr>
                    <tr><th>Review Period:</th><td>01 Jan {{ $period->year }} – Dec {{ $period->year }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- KEY TASKS --}}
    <h3>Key Tasks & Ratings</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Key Task</th>
            <th>Task</th>
            <th>Self Rating</th>
            <th>Self Comment</th>
            <th>Assessor Rating</th>
            <th>Assessor Comment</th>
            <th>Update</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $grouped = $initiatives->groupBy('target_id'); 
            $totalSelf = 0; $countSelf = 0;
            $totalAssessor = 0; $countAssessor = 0;
        @endphp

        @forelse($grouped as $targetId => $tasks)
            <form action="{{ route('targets.assessorUpdateInline', $targetId) }}" method="POST">
                @csrf
                @method('PATCH')
                @foreach($tasks as $i => $task)
                    <tr>
                        @if($i === 0)
                            {{-- Key Task --}}
                            <td rowspan="{{ $tasks->count() }}">{{ $task->target->target_name ?? '-' }}</td>
                        @endif

                        {{-- Task --}}
                        <td>{{ $task->initiative }}</td>

                        {{-- Self-assessment read-only --}}
                        @if($i === 0)
                            <td rowspan="{{ $tasks->count() }}">
                                @switch($task->target->self_rating)
                                    @case(6) A1 @break
                                    @case(5) A2 @break
                                    @case(4) B1 @break
                                    @case(3) B2 @break
                                    @case(2) C1 @break
                                    @case(1) C2 @break
                                    @default - 
                                @endswitch
                            </td>
                            <td rowspan="{{ $tasks->count() }}">
                                {{ $task->target->self_comment ?? '-' }}
                            </td>

                            {{-- Assessor editable --}}
                            <td rowspan="{{ $tasks->count() }}">
                                <select name="assessor_rating" class="form-control">
                                    <option value="">-- Select Rating --</option>
                                    @for($r=6; $r>=1; $r--)
                                        <option value="{{ $r }}" {{ $task->target->assessor_rating == $r ? 'selected' : '' }}>
                                            @switch($r)
                                                @case(6) A1 @break
                                                @case(5) A2 @break
                                                @case(4) B1 @break
                                                @case(3) B2 @break
                                                @case(2) C1 @break
                                                @case(1) C2 @break
                                            @endswitch
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td rowspan="{{ $tasks->count() }}">
                                <input type="text" name="assessor_comment" value="{{ $task->target->assessor_comment }}" class="form-control">
                            </td>

                            {{-- Save button --}}
                            <td rowspan="{{ $tasks->count() }}">
                                <button type="submit" class="btn btn-sm btn-success">Save</button>
                            </td>
                        @endif

                        {{-- Accumulate ratings for overall --}}
                        @php
                            if($task->target->self_rating) { $totalSelf += $task->target->self_rating; $countSelf++; }
                            if($task->target->assessor_rating) { $totalAssessor += $task->target->assessor_rating; $countAssessor++; }
                        @endphp
                    </tr>
                @endforeach
            </form>
        @empty
            <tr><td colspan="7">No tasks found.</td></tr>
        @endforelse

        {{-- Overall Ratings for all Key Tasks --}}
        @php
            $overallSelf = $countSelf ? $totalSelf / $countSelf : null;
            $overallAssessor = $countAssessor ? $totalAssessor / $countAssessor : null;

            $grade = function($num) {
                if($num === null) return '-';
                if($num >= 5.5) return 'A1';
                if($num >= 4.5) return 'A2';
                if($num >= 3.5) return 'B1';
                if($num >= 2.5) return 'B2';
                if($num >= 1.5) return 'C1';
                if($num >= 0.5) return 'C2';
                return '-';
            };
        @endphp
        <tr style="font-weight: bold; background-color: #f1f1f1;">
            <td colspan="2">Overall Rating (All Key Tasks)</td>
            <td>{{ $grade($overallSelf) }}</td>
            <td></td>
            <td>{{ $grade($overallAssessor) }}</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>



    {{-- MY RATINGS --}}
    <form action="{{ route('manager.ratings.saveAssessor') }}" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">

    @foreach($sectionRatingsForMyRatings as $s)
        <h4 class="mt-4">Section: {{ $s['section']->name }}</h4>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Self Rating</th>
                    <th>Self Comment</th>
                    <th>Assessor Rating</th>
                    <th>Assessor Comment</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $assessorTotal = 0;
                    $assessorCount = 0;
                @endphp

                @foreach($s['section']->tasks as $task)
                    @php $rating = $task->ratings->first(); @endphp
                    @if($rating?->assessor_rating)
                        @php
                            $assessorTotal += $rating->assessor_rating;
                            $assessorCount++;
                        @endphp
                    @endif
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>
                            @switch($rating?->self_rating)
                                @case(6) A1 @break
                                @case(5) A2 @break
                                @case(4) B1 @break
                                @case(3) B2 @break
                                @case(2) C1 @break
                                @case(1) C2 @break
                                @default - 
                            @endswitch
                        </td>
                        <td>{{ $rating?->self_comment ?? '-' }}</td>

                        <td>
                            <select name="ratings[{{ $task->id }}][assessor_rating]" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="6" {{ ($rating?->assessor_rating == 6) ? 'selected' : '' }}>A1</option>
                                <option value="5" {{ ($rating?->assessor_rating == 5) ? 'selected' : '' }}>A2</option>
                                <option value="4" {{ ($rating?->assessor_rating == 4) ? 'selected' : '' }}>B1</option>
                                <option value="3" {{ ($rating?->assessor_rating == 3) ? 'selected' : '' }}>B2</option>
                                <option value="2" {{ ($rating?->assessor_rating == 2) ? 'selected' : '' }}>C1</option>
                                <option value="1" {{ ($rating?->assessor_rating == 1) ? 'selected' : '' }}>C2</option>
                            </select>
                        </td>

                        <td>
                            <input type="text" name="ratings[{{ $task->id }}][assessor_comment]"
                                   value="{{ $rating?->assessor_comment }}" class="form-control">
                        </td>
                    </tr>
                @endforeach

                {{-- Overall Ratings for Section --}}
                @php
                    $assessorAvg = $assessorCount ? $assessorTotal / $assessorCount : null;

                    // Convert numeric average to grade label
                    $label = function($num) {
                        if($num === null) return '-';
                        if($num >= 5.5) return 'A1';
                        if($num >= 4.5) return 'A2';
                        if($num >= 3.5) return 'B1';
                        if($num >= 2.5) return 'B2';
                        if($num >= 1.5) return 'C1';
                        if($num >= 0.5) return 'C2';
                        return '-';
                    };
                @endphp

                <tr style="background-color:#f2f2f2; font-weight:bold;">
                    <td>Overall Rating for Section</td>
                    <td>{{ $s['label'] }}</td>
                    <td></td>
                    <td colspan="2">{{ $label($assessorAvg) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <button type="submit" class="btn btn-primary">Save All Assessor Ratings</button>
</form>

{{-- SUMMARY --}}
<h3>SECTION 4</h3>
<h5>Summary Ratings for Period End Performance Review <small>(Data brought forward from previous sections)</small></h5>
<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>Balanced Scorecard Perspective</th>
            <th>Overall Ratings of Staff member being Assessed (Self)</th>
            <th>Overall Ratings of Assessor</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sectionRatings as $section)
            <tr>
                <td>{{ $section['name'] }}</td>
                <td>{{ $section['label'] }}</td>
                <td>
                    @switch($section['assessor_average'])
                        @case(6) A1 @break
                        @case(5) A2 @break
                        @case(4) B1 @break
                        @case(3) B2 @break
                        @case(2) C1 @break
                        @case(1) C2 @break
                        @default {{ $section['assessor_label'] ?? '-' }}
                    @endswitch
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No sections / ratings found.</td>
            </tr>
        @endforelse
        <tr style="font-weight: bold;">
            <td>Total Performance Notches</td>
            <td>{{ $totalSelfLabel }}</td>
            <td>{{ $totalAssessorLabel }}</td>
        </tr>
    </tbody>
</table>


{{-- ================== REPORT GENERATION ================== --}}
    <hr>
    <h3>Generate Report</h3>
    <form method="POST" action="{{ route('appraisalreport.apgenerate') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="form-group">
            <label for="period_id">Select Period:</label>
            <select name="period_id" class="form-control" required>
                @foreach(\App\Models\Period::all() as $p)
                    <option value="{{ $p->id }}">{{ $p->year }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-info">Generate Report</button>
    </form>
    {{-- Approval Actions --}}
<hr>
<h3>Approval Actions</h3>

@php
    // Get the latest authorisation for this period
    $latestAuth = $authorisations->where('period_id', $purposes->first()->period_id ?? 1)
                                  ->sortByDesc('created_at')
                                  ->first();
@endphp

@if($latestAuth && $latestAuth->status === 'Pending')
    {{-- Approve Button --}}
    <form method="POST" action="{{ route('manager.appraisals.approve', [$user->id, $purposes->first()->period_id ?? 1]) }}" style="display:inline;">
    @csrf
    <input type="hidden" name="total_self_label" value="{{ $totalSelfLabel }}">
    <input type="hidden" name="total_assessor_label" value="{{ $totalAssessorLabel }}">
    <button type="submit" class="btn btn-success">Authorize</button>
</form>


    {{-- Reject Button (Triggers Modal) --}}
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
        Reject
    </button>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form method="POST" action="{{ route('manager.appraisals.reject', [$user->id, $purposes->first()->period_id ?? 1]) }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to reject this user’s performance data?</p>
              <div class="form-group">
                  <label for="comment">Rejection Comment</label>
                  <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger">Confirm Reject</button>
            </div>
          </form>
        </div>
      </div>
    </div>
@endif

</div>
</section>
</div>
</div>
@endsection