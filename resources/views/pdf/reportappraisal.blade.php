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

<center>
   
    <h1>LOCAL AUTHORITIES PENSION FUND</h1>
    <h1>{{ $period->year }} PERFORMANCE APPRAISAL</h1>
</center>

<table>
    <tr>
        <th><strong>Purpose</strong></th>
        <th><strong>Employee Details</strong></th>
    </tr>
    <tr>
        <td>
            
                @foreach($purposes as $purpose)
                    {!! $purpose->purpose !!}
                @endforeach
            
        </td>
        <td>
            <p><strong>Name of Employee:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Department:</strong> {{ $user->department }}</p>
            <p><strong>Section:</strong> {{ $user->section }}</p>
            <p><strong>Position:</strong> {{ $user->jobtitle }}</p>
            <p><strong>Job Grade:</strong> {{ $user->grade }}</p>
        </td>
    </tr>
</table>

{{-- Rating Labels --}}
@php
    $labels = [
        6 => 'A1',
        5 => 'A2',
        4 => 'B1',
        3 => 'B2',
        2 => 'C1',
        1 => 'C2',
    ];
@endphp

{{-- Loop objectives by target --}}
@foreach($objectives as $objective)
    <h3>{{ $objective->target->target_name ?? 'No Target' }}</h3>
    <table>
        <tr>
            <th>Objective</th>
            <th>Action / Initiative</th>
            <th>Target / Budget</th>
            <th>Achieved</th>
            <th>Rating</th>
            <th>Comment</th>
        </tr>
        @forelse($objective->initiatives as $initiative)
            <tr>
                <td>{{ $objective->objective }}</td>
                <td>{{ $initiative->initiative }}</td>
                <td>{{ $initiative->budget }}</td>
                <td>{{ $initiative->archieved ? 'Yes' : 'No' }}</td>
                <td>{{ $labels[$initiative->rating] ?? 'Not Rated' }}</td>
                <td>{{ $initiative->comment }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No initiatives added.</td>
            </tr>
        @endforelse
    </table>
@endforeach

{{-- Summary Table --}}
@php
    // Build per-target averages
    $summary = $objectives->map(function($objective) use ($labels) {
        $empAvg = $objective->initiatives->avg('rating');
        $supAvg = $objective->initiatives->avg('supervisorrating');

        return [
            'target_name' => $objective->target->target_name ?? 'No Target',
            'empAvg' => $empAvg ? ($labels[round($empAvg)] ?? 'Not Rated') : 'Not Rated',
            'supAvg' => $supAvg ? ($labels[round($supAvg)] ?? 'Not Rated') : 'Not Rated',
        ];
    });

    // Overall averages
    $overallEmp = $objectives->flatMap->initiatives->avg('rating');
    $overallSup = $objectives->flatMap->initiatives->avg('supervisorrating');

    $overallEmpLabel = $overallEmp ? ($labels[round($overallEmp)] ?? 'Not Rated') : 'Not Rated';
    $overallSupLabel = $overallSup ? ($labels[round($overallSup)] ?? 'Not Rated') : 'Not Rated';
@endphp

<h5>Summary Ratings for Period End Performance Review <small>(Data brought forward from previous sections)</small></h5>
<p>Note final ratings used for the performance notching on pay scales or bonuses will be those of the reviewer, and will be subject to the approval of the Human Resources Department.</p>
<table>
    <tr>
        <th>Balanced Scorecard Perspective</th>
        <th>Overall Ratings of Staff member being Assessed</th>
        <th>Overall Ratings of Assessor</th>
    </tr>
    @foreach($summary as $row)
        <tr>
            <td>{{ $row['target_name'] }}</td>
            <td>{{ $row['empAvg'] }}</td>
            <td>{{ $row['supAvg'] }}</td>
        </tr>
    @endforeach
    <tr>
        <th>Total Performance Notches</th>
        <th>{{ $overallEmpLabel }}</th>
        <th>{{ $overallSupLabel }}</th>
    </tr>
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
