<!DOCTYPE html>
<html>
<head>
    <title>Performance Appraisal</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            border-radius: 50%;
        }
        .average-rating {
            margin-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<style>
    .user-details {
        width: 100%;
        border-collapse: collapse;
        background-color: #f8f9fa; /* light grey */
        margin-bottom: 20px;
    }
    .user-details th,
    .user-details td {
        border: 1px solid #fff; /* white borders */
        padding: 8px;
        text-align: left;
        vertical-align: top;
    }
    .user-details th {
        font-weight: bold;
        width: 40%;
    }
</style>
<h4>PERFORMANCE ASSESSMENT FORM FOR NON-MANAGERIAL STAFF – LOCAL AUTHORITIES PENSION FUND </h4>
<h3>SECTION 1: JOB INFORMATION </h3>

<div style="width: 100%; background-color: #d6d6d6; padding: 0; margin: 0;">
    <table style="width: 100%; border-collapse: collapse;">
        <!-- Name & Assessor Row -->
        <tr style="border-bottom: 2px solid white;">
            <td style="padding: 6px; font-weight: bold; width: 20%; border-right: none;">Name of Staff Member:</td>
            <td style="padding: 6px; width: 30%; border-right: none;">{{ $user->first_name }} {{ $user->last_name }}</td>

            <td style="padding: 6px; font-weight: bold; width: 20%; border-left: 2px solid white;">Assessor:</td>
            <td style="padding: 6px; width: 30%;">{{ $user->supervisor ? $user->supervisor->first_name . ' ' . $user->supervisor->last_name : 'N/A' }}</td>
        </tr>

        <!-- Department & Reviewer Row -->
        <tr style="border-bottom: 2px solid white;">
            <td style="padding: 6px; font-weight: bold;">Department:</td>
            <td style="padding: 6px;">{{ $user->department }}</td>

            <td style="padding: 6px; font-weight: bold; border-left: 2px solid white;">Reviewer:</td>
            <td style="padding: 6px;">{{ $user->reviewer ? $user->reviewer->first_name . ' ' . $user->reviewer->last_name : 'N/A' }}</td>
        </tr>

        <!-- Section & Review Period Row -->
        <tr style="border-bottom: 2px solid white;">
            <td style="padding: 6px; font-weight: bold;">Section:</td>
            <td style="padding: 6px;">{{ $user->section }}</td>

            <td style="padding: 6px; font-weight: bold; border-left: 2px solid white;">Review Period:</td>
            <td style="padding: 6px;">From 01 January {{ $period->year }} to December {{ $period->year }}</td>
        </tr>

        <!-- Job Title & Empty -->
        <tr style="border-bottom: 2px solid white;">
            <td style="padding: 6px; font-weight: bold;">Job Title:</td>
            <td style="padding: 6px;">{{ $user->jobtitle }}</td>

            <td style="padding: 6px; font-weight: bold; border-left: 2px solid white;"></td>
            <td style="padding: 6px;"></td>
        </tr>

        <!-- Grade & Empty -->
        <tr style="border-bottom: 2px solid white;">
            <td style="padding: 6px; font-weight: bold;">Grade:</td>
            <td style="padding: 6px;">{{ $user->grade }}</td>

            <td style="padding: 6px; font-weight: bold; border-left: 2px solid white;"></td>
            <td style="padding: 6px;"></td>
        </tr>
    </table>
</div>



<style>
    .rating-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .rating-table th {
        background-color: #d6d6d6; /* dark grey */
        color: #000000;
        padding: 8px;
        text-align: left;
        border: none; /* remove borders from headers */
    }
    .rating-table td {
        padding: 8px;
        border: none; /* remove all borders from table cells */
    }
    .rating-table tr:nth-child(even) td {
        background-color: #f8f9fa; /* optional light grey for alternating rows */
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

<h3>SECTION 2 : SUMMARY OF PERFORMANCE ON TASKS</h3>
<table class="table table-bordered" style="width:100%; table-layout: fixed; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width:50%;">Key Task</th>
            <th style="width:25%;">Self Assessment - Rating</th>
            <th style="width:25%;">Self Assessment - Comment</th>
        </tr>
    </thead>
    <tbody>
        @php
            $ratingMap = [6=>'A1',5=>'A2',4=>'B1',3=>'B2',2=>'C1',1=>'C2'];
            $totalRating = 0;
            $ratingCount = 0;
        @endphp

        @forelse($objectives as $objective)
            @php
                $initiatives = $objective->initiatives;
                $initiativeList = $initiatives->pluck('initiative')->implode('<br>') ?: '-';
            @endphp

            <tr>
                {{-- Key Task with initiatives --}}
                <td>
                    <strong>{{ $objective->target->target_name ?? '-' }}</strong><br>
                    <small>{!! $initiativeList !!}</small>
                </td>

                {{-- Self Rating --}}
                <td>
                    {{ $objective->target->self_rating ? $ratingMap[$objective->target->self_rating] : 'Not Rated' }}
                </td>

                {{-- Self Comment --}}
                <td>{{ $objective->target->self_comment ?? '-' }}</td>

                {{-- Accumulate rating for overall --}}
                @php
                    if($objective->target->self_rating) {
                        $totalRating += $objective->target->self_rating;
                        $ratingCount++;
                    }
                @endphp
            </tr>
        @empty
            <tr>
                <td colspan="3">&nbsp;No Objectives Available</td>
            </tr>
        @endforelse

        {{-- Overall Rating --}}
        @php
            $overall = $ratingCount > 0 ? $totalRating / $ratingCount : null;
            $overallLabel = $overall ? $ratingMap[(int) round($overall)] : 'N/A';
        @endphp
        <tr style="font-weight:bold; background-color:#f1f1f1;">
            <td>Overall Rating</td>
            <td>{{ $overallLabel }}</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>



@foreach($sections as $section)
    <h4 class="mt-4">Section: {{ $section->name }}</h4>
    <table class="table table-bordered mb-4" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color:#d6d6d6;">
                <th style="width:40%;">Task</th>
                <th style="width:30%;">Self Assessment - Rating</th>
                <th style="width:30%;">Self Assessment - Rating Comment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($section->tasks as $task)
                @php 
                    $rating = $task->ratings->first(); 
                    $selfRatingLabel = $rating ? $gradeFromNumber($rating->self_rating) : '-';
                    $selfComment = $rating ? $rating->self_comment : '-';
                @endphp
                <tr>
                    <td>{{ $task->name }}</td>
                    <td>{{ $selfRatingLabel }}</td>
                    <td>{{ $selfComment }}</td>
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

                $overallSectionLabel = $overallSection ? $gradeFromNumber($overallSection) : '-';
            @endphp
            <tr style="background-color:#f2f2f2; font-weight:bold;">
                <td>Overall Rating for Section {{ $section->name }}</td>
                <td>{{ $overallSectionLabel }}</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
@endforeach



<hr>
<h3>Section 3</h3>
<h3>General Summary</h3>
<p><strong>A.</strong>This section summarises what are perceived to be the staff member’s major strengths and learning and development areas. </p>

<table style="width:100%; border-collapse: collapse;" border="1">
    <thead>
        <tr style="background-color:#d6d6d6;">
            <th style="padding:6px;">Strengths as Perceived by the Staff Member Being Assessed</th>
            <th style="padding:6px;">Learning Areas as Perceived by the Staff Member Being Assessed</th>
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
                <td style="padding:6px;">{{ $strength->strength ?? '-' }}</td>
                <td style="padding:6px;">{{ $learning->learning_area ?? '-' }}</td>
            </tr>
        @endfor
    </tbody>
</table>

{{-- Summary Table --}}
@php
    $summary = $objectives->map(function($objective) use ($ratingLabels) {
        $empAvg = $objective->initiatives->avg('rating');
        $supAvg = $objective->initiatives->avg('supervisorrating');

        return [
            'target_name' => $objective->target->target_name ?? 'No Target',
            'empAvg' => $empAvg ? ($ratingLabels[round($empAvg)] ?? 'Not Rated') : 'Not Rated',
            'supAvg' => $supAvg ? ($ratingLabels[round($supAvg)] ?? 'Not Rated') : 'Not Rated',
        ];
    });

    $overallEmp = $objectives->flatMap->initiatives->avg('rating');
    $overallSup = $objectives->flatMap->initiatives->avg('supervisorrating');

    $overallEmpLabel = $overallEmp ? ($ratingLabels[round($overallEmp)] ?? 'Not Rated') : 'Not Rated';
    $overallSupLabel = $overallSup ? ($ratingLabels[round($overallSup)] ?? 'Not Rated') : 'Not Rated';
@endphp

<h3>SECTION 4</h3>
<h5>Summary Ratings for Period End Performance Review <small>(Data brought forward from previous sections)</small></h5>
<p>Note final ratings used for the performance notching on pay scales or bonuses will be those of the reviewer, and will be subject to the approval of the Human Resources Department.</p>

<table style="width:100%; border-collapse: collapse;" border="1">
    <thead style="background-color:#d6d6d6;">
        <tr>
            <th>Balanced Scorecard Perspective</th>
            <th>Overall Ratings of Staff member being Assessed</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sectionRatings as $section)
        <tr>
            <td style="padding:6px;">{{ $section['name'] }}</td>
            <td style="padding:6px;">{{ $section['label'] }}</td>
        </tr>
        @endforeach
        <tr style="font-weight:bold; background-color:#f1f1f1;">
            <td>Total Performance Notches</td>
            <td>{{ $totalPerformanceNotchesLabel }}</td>
        </tr>
    </tbody>
</table>



<h4>Signatures</h4>
<p>After discussing this assessment of the staff member’s overall performance, and after the reviewer assigns the final performance rating, both the staff member being assessed and Assessor sign here as confirmation of the discussion and confirmation they have seen the final ratings and reviewers comments. The Reviewer also signs.</p>
<table style="width: 100%; margin-top: 30px;">
    <tr>
        <!-- Left side -->
        <td style="width: 50%; text-align: left; vertical-align: bottom;">
            <p>Incumbent’s Electronic Signature</p>
           

            {{-- ✅ Show approved user(s) --}}
            @foreach($authorisations->where('status', 'Authorized') as $authorisation)
    <p><strong>{{ $authorisation->user->name }} </strong></p>
@endforeach
            <p>__________________________</p>
            <label>Date:</label>
            @if($authorisations->first())
                {{ \Carbon\Carbon::parse($authorisations->first()->created_at)->format('d-m-Y') }}
            @endif
            
        </td>

        <!-- Right side -->
        <td style="width: 50%; text-align: right; vertical-align: bottom;">
            <p>Superior’s Electronic Signature</p>
            @foreach($superiors as $superior)
                <p><strong>{{ $superior->name }}</strong></p>
            @endforeach
            <p>__________________________</p>
            <label>Date:</label>
            @if($authorisations->first())
                {{ \Carbon\Carbon::parse($authorisations->first()->updated_at)->format('d-m-Y') }}
            @endif
        </td>
    </tr>
</table>

</body>
</html>
