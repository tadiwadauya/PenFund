@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">
<h1>PERFOMANCE ASSESSMENT FOR  STAFF - LOCAL AUTHORITIES PENSION FUND</h1>
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
</br>
<h2>SECTION 1</h2>
{{-- ================== USER DETAILS ================== --}}
<style>
    .table-white-border td, 
    .table-white-border th {
        border: 1px solid #fff !important; /* white borders */
    }
</style>

<table class="table table-bordered mb-4 table-white-border">
    <tr class="table-secondary">
        <!-- Employee Details (Left) -->
        <td width="50%">
            <table class="table table-sm mb-0 table-white-border">
                <tr>
                    <th width="40%">Name of Staff Member:</th>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                </tr>
                <tr>
                    <th>Department:</th>
                    <td>{{ $user->department }}</td>
                </tr>
                <tr>
                    <th>Section:</th>
                    <td>{{ $user->section }}</td>
                </tr>
                <tr>
                    <th>Job Title:</th>
                    <td>{{ $user->jobtitle }}</td>
                </tr>
                <tr>
                    <th>Grade:</th>
                    <td>{{ $user->grade }}</td>
                </tr>
            </table>
        </td>

        <!-- Supervisor Details (Right) -->
        <td width="50%">
            <table class="table table-sm mb-0 table-white-border">
                <tr>
                    <th width="30%">Assessor:</th>
                    <td>{{ $user->supervisor ? $user->supervisor->first_name . ' ' . $user->supervisor->last_name : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Reviewer:</th>
                    <td>{{ $user->reviewer ? $user->reviewer->first_name . ' ' . $user->reviewer->last_name : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Review Period:</th>
                    <td>From 01 January {{ $period->year }} to December {{ $period->year }}</td>
                </tr>
            </table>
        </td>
    </tr>
    
</table>


<style>
    .rating-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .rating-table th {
        background-color: #6c757d; /* dark grey */
        color: #fff;
        padding: 8px;
        text-align: left;
    }
    .rating-table td {
        padding: 8px;
        border: 1px solid #dee2e6;
    }
    .rating-table tr:nth-child(even) td {
        background-color: #f8f9fa; /* light grey rows */
    }
</style>

<table class="rating-table">
    <thead>
        <tr>
            <th colspan="2">Rating scale for use throughout the form</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>A1</td>
            <td>Outstanding performance. High levels of expertise</td>
        </tr>
        <tr>
            <td>A2</td>
            <td>Consistently exceeds requirements</td>
        </tr>
        <tr>
            <td>B1</td>
            <td>Meets requirements. Occasionally exceeds them</td>
        </tr>
        <tr>
            <td>B2</td>
            <td>Meets requirements.</td>
        </tr>
        <tr>
            <td>C1</td>
            <td>Partially meets requirements. Improvement required</td>
        </tr>
        <tr>
            <td>C2</td>
            <td>Unacceptable. Well below standard required</td>
        </tr>
    </tbody>
</table>

    <br>
<hr>
@if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Key Task</th>
            <th>Task</th>
            <th>Self Assessment Rating</th>
            <th>Self Comment</th>
            <th>Update</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grouped = $initiatives->groupBy('target_id');
            $totalRating = 0;
            $ratingCount = 0;
        @endphp

        @forelse($grouped as $targetId => $tasks)
            <form action="{{ route('targets.updateInline', $targetId) }}" method="POST">
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

                        {{-- Self Rating --}}
                        @if($i === 0)
                            <td rowspan="{{ $tasks->count() }}">
                                <select name="self_rating" class="form-control" style="width: 120px;">
                                    <option value="">-- Select Rating --</option>
                                    <option value="6" {{ $task->target->self_rating == 6 ? 'selected' : '' }}>A1</option>
                                    <option value="5" {{ $task->target->self_rating == 5 ? 'selected' : '' }}>A2</option>
                                    <option value="4" {{ $task->target->self_rating == 4 ? 'selected' : '' }}>B1</option>
                                    <option value="3" {{ $task->target->self_rating == 3 ? 'selected' : '' }}>B2</option>
                                    <option value="2" {{ $task->target->self_rating == 2 ? 'selected' : '' }}>C1</option>
                                    <option value="1" {{ $task->target->self_rating == 1 ? 'selected' : '' }}>C2</option>
                                </select>
                            </td>

                            {{-- Self Comment --}}
                            <td rowspan="{{ $tasks->count() }}">
                                <input type="text" name="self_comment" value="{{ $task->target->self_comment }}" class="form-control">
                            </td>

                            {{-- Save Button --}}
                            <td rowspan="{{ $tasks->count() }}">
                                <button type="submit" class="btn btn-sm btn-success">Save</button>
                            </td>
                        @endif

                        {{-- Accumulate rating for overall --}}
                        @php
                            if($task->target->self_rating) {
                                $totalRating += $task->target->self_rating;
                                $ratingCount++;
                            }
                        @endphp
                    </tr>
                @endforeach
            </form>
        @empty
            <tr>
                <td colspan="5">No Actions found.</td>
            </tr>
        @endforelse

        {{-- Overall Rating --}}
        @php
            $overall = $ratingCount > 0 ? $totalRating / $ratingCount : null;
            $overallLabel = '';
            if($overall !== null) {
                $rounded = (int) round($overall);
                switch($rounded) {
                    case 6: $overallLabel = 'A1'; break;
                    case 5: $overallLabel = 'A2'; break;
                    case 4: $overallLabel = 'B1'; break;
                    case 3: $overallLabel = 'B2'; break;
                    case 2: $overallLabel = 'C1'; break;
                    case 1: $overallLabel = 'C2'; break;
                    default: $overallLabel = 'N/A'; break;
                }
            } else {
                $overallLabel = 'N/A';
            }
        @endphp
        <tr style="font-weight: bold; background-color: #f1f1f1;">
            <td colspan="2">Overall Rating</td>
            <td>{{ $overallLabel }}</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>





    <hr>
    <a href="{{ route('evaluation_sections.create') }}" class="btn btn-success mb-3">Add Section</a>
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Add Task</a>
    <hr>
    <h2>My Ratings</h2>

<form action="{{ route('ratings.saveAll') }}" method="POST">
    @csrf

    @foreach($sections as $section)
        <h4 class="mt-4">Section: {{ $section->name }}</h4>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Self Rating</th>
                    <th>Self Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($section->tasks as $task)
                    @php 
                        $rating = $task->ratings->first(); 
                    @endphp
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>
                            <select name="ratings[{{ $task->id }}][self_rating]" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="6" {{ ($rating && $rating->self_rating == 6) ? 'selected' : '' }}>A1</option>
                                <option value="5" {{ ($rating && $rating->self_rating == 5) ? 'selected' : '' }}>A2</option>
                                <option value="4" {{ ($rating && $rating->self_rating == 4) ? 'selected' : '' }}>B1</option>
                                <option value="3" {{ ($rating && $rating->self_rating == 3) ? 'selected' : '' }}>B2</option>
                                <option value="2" {{ ($rating && $rating->self_rating == 2) ? 'selected' : '' }}>C1</option>
                                <option value="1" {{ ($rating && $rating->self_rating == 1) ? 'selected' : '' }}>C2</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" 
                                   name="ratings[{{ $task->id }}][self_comment]" 
                                   value="{{ $rating ? $rating->self_comment : '' }}" 
                                   class="form-control">
                        </td>
                    </tr>
                @endforeach

                {{-- Overall Section Rating --}}
                @php
                    $overallSection = collect($section->tasks)->map(function($task){
                        $rating = $task->ratings->first();
                        if ($rating) {
                            return collect([
                                $rating->self_rating, 
                                $rating->assessor_rating, 
                                $rating->reviewer_rating
                            ])->filter()->avg();
                        }
                        return null;
                    })->filter()->avg();
                @endphp
                <tr style="background-color:#f2f2f2; font-weight:bold;">
                    <td>Overall Rating for Section</td>
                    <td>{{ $overallSection ? $gradeFromNumber($overallSection) : '-' }}</td>
                    <td colspan="5"></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <button type="submit" class="btn btn-primary">Save All Ratings</button>
</form>

<hr>
<h2>Strengths & Learning Areas</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- ================= Self-Perception ================= --}}
<h3>My Perception</h3>
<div class="row mb-3">
    <div class="col-md-6">
        <form method="POST" action="{{ route('strengths.store') }}">
            @csrf
            <div class="input-group mb-2">
                <textarea name="strength" class="form-control" placeholder="Add a strength"></textarea>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <form method="POST" action="{{ route('strengths.learning.storeLearningArea') }}">
            @csrf
            <div class="input-group mb-2">
                <textarea name="learning_area" class="form-control" placeholder="Add a learning area"></textarea>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>
<h3>SECTION 3</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Strength</th>
            <th>Learning Area</th>
        </tr>
    </thead>
    <tbody>
        @php $max = max($selfStrengths->count(), $selfLearning->count()); @endphp
        @for($i = 0; $i < $max; $i++)
            @php
                $strength = $selfStrengths[$i] ?? null;
                $learning = $selfLearning[$i] ?? null;
            @endphp
            <tr>
                {{-- Strength --}}
                <td>
                    @if($strength)
                    <div class="d-flex mb-1">
                        <form method="POST" action="{{ route('strengths.update', $strength->id) }}" class="flex-grow-1 me-1">
                            @csrf
                            @method('PATCH')
                            <textarea name="strength" class="form-control">{{ $strength->strength }}</textarea>
                            <button type="submit" class="btn btn-success mt-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('strengths.destroy', $strength->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-1" onclick="return confirm('Delete this strength?')">Delete</button>
                        </form>
                    </div>
                    @endif
                </td>

                {{-- Learning --}}
                <td>
                    @if($learning)
                    <div class="d-flex mb-1">
                        <form method="POST" action="{{ route('learning.update', $learning->id) }}" class="flex-grow-1 me-1">
                            @csrf
                            @method('PATCH')
                            <textarea name="learning_area" class="form-control">{{ $learning->learning_area }}</textarea>
                            <button type="submit" class="btn btn-success mt-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('learning.destroy', $learning->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-1" onclick="return confirm('Delete this learning area?')">Delete</button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
        @endfor
    </tbody>
</table>

<hr>
<!-- {{-- ================= Assessor Perception ================= --}}
<h3>Assessor Perception</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Strength</th>
            <th>Learning Area</th>
        </tr>
    </thead>
    <tbody>
        @php $max = max($assessorStrengths->count(), $assessorLearning->count()); @endphp
        @for($i = 0; $i < $max; $i++)
            @php
                $strength = $assessorStrengths[$i] ?? null;
                $learning = $assessorLearning[$i] ?? null;
            @endphp
            <tr>
                {{-- Strength --}}
                <td>
                    @if($strength)
                    <div class="d-flex mb-1">
                        <form method="POST" action="{{ route('strengths.assessor.update', $strength->id) }}" class="flex-grow-1 me-1">
                            @csrf
                            @method('PATCH')
                            <textarea name="strength" class="form-control">{{ $strength->strength }}</textarea>
                            <button type="submit" class="btn btn-success mt-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('strengths.assessor.destroy', $strength->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-1" onclick="return confirm('Delete this strength?')">Delete</button>
                        </form>
                    </div>
                    @endif
                </td>

                {{-- Learning --}}
                <td>
                    @if($learning)
                    <div class="d-flex mb-1">
                        <form method="POST" action="{{ route('learning.assessor.update', $learning->id) }}" class="flex-grow-1 me-1">
                            @csrf
                            @method('PATCH')
                            <textarea name="learning_area" class="form-control">{{ $learning->learning_area }}</textarea>
                            <button type="submit" class="btn btn-success mt-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('learning.assessor.destroy', $learning->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-1" onclick="return confirm('Delete this learning area?')">Delete</button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
        @endfor
    </tbody>
</table> -->
<h3>SECTION 4</h3>
<h5>Summary Ratings for Period End Performance Review <small>(Data brought forward from previous sections)</small></h5>
<p>Note final ratings used for the performance notching on pay scales or bonuses will be those of the reviewer, and will be subject to the approval of the Human Resources Department.</p>

<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>Balanced Scorecard Perspective</th>
            <th>Overall Ratings of Staff member being Assessed</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sectionRatings as $section)
        <tr>
            <td>{{ $section['name'] }}</td>
            <td>{{ $section['label'] }}</td>
        </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td>Total Performance Notches</td>
            <td>{{ $totalPerformanceNotchesLabel }}</td>
        </tr>
    </tbody>
</table>


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
