<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            border-radius: 50%;
        }
    </style>
</head>
<body>

<center>
   
    <h1>LOCAL AUTHORITIES PENSION FUND</h1>
    <h1>2025 PERFORMANCE CONTRACT</h1>
</center>

<table>
    <tr>
        <th><strong>Purpose</strong></th>
        <th><strong>Employee Details</strong></th>
    </tr>
    <tr>
        <td>
            <ul>
                @foreach($purposes as $purpose)
                    {!! $purpose->purpose !!}
                @endforeach
            </ul>
        </td>
        <td>
            <p><strong>Name of Employee:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Department:</strong> {{ $user->department }}</p>
            <p><strong>Section:</strong> {{ $user->section }}</p>
            <p><strong>Position:</strong> {{ $user->jobtitle }}</p>
            <p><strong>Job Grade:</strong> {{ $user->grade }}</p>
            <p><strong>Superior Position:</strong> {{ $user->supervisor ? $user->supervisor->first_name . ' ' . $user->supervisor->last_name : 'N/A' }}</p>
            <p><strong>Superior Job Grade:</strong> {{ $user->supervisor ? $user->supervisor->grade : 'N/A' }}</p>
        </td>
    </tr>
</table>
<h5>Rating Scale for Use Throughout the Form:</h5>
<ul>
    <li><strong>A1:</strong> Outstanding performance. High levels of expertise.</li>
    <li><strong>A2:</strong> Consistently exceeds requirements.</li>
    <li><strong>B1:</strong> Meets requirements. Occasionally exceeds them.</li>
    <li><strong>B2:</strong> Meets requirements.</li>
    <li><strong>C1:</strong> Partially meets requirements. Improvement required.</li>
    <li><strong>C2:</strong> Unacceptable. Well below standard required.</li>
</ul>



{{-- Loop objectives and initiatives directly --}}
@foreach($objectives as $objective)
    <h3>{{ $objective->target->target_name ?? 'No Target' }}</h3>
    <table>
        <tr>
            <th><strong>Objective</strong></th>
            <th><strong>Actions to Support Objectives</strong></th>
            <th><strong>Target/ Budget</strong></th>
        </tr>

        @forelse($objective->initiatives as $initiative)
            <tr>
                <td>{{ $objective->objective }}</td>
                <td>{{ $initiative->initiative }}</td>
                <td>{{ $initiative->budget }}</td>
            </tr>
        @empty
            <tr>
                <td>{{ $objective->objective }}</td>
                <td colspan="2">No initiatives added</td>
            </tr>
        @endforelse
    </table>
@endforeach

{{-- If no objectives at all --}}
@if($objectives->isEmpty())
    <p><em>No objectives available for this period.</em></p>
@endif

<h4>Signatures</h4>
<p>After discussing this assessment of the staff member’s overall performance, and after the reviewer assigns the final performance rating, both the staff member being assessed and Assessor sign here as confirmation of the discussion and confirmation they have seen the final ratings and reviewers comments. The Reviewer also signs.</p>
<table style="width: 100%; margin-top: 30px;">
    <tr>
        <!-- Left side -->
        <td style="width: 50%; text-align: left; vertical-align: bottom;">
            <p>Incumbent’s Electronic Signature</p>
           

            {{-- ✅ Show approved user(s) --}}
            @foreach($user->approvals->where('status', 'Approved') as $approval)
                <p><strong>{{ $approval->user->name }} </strong></p>
            @endforeach
            <p>__________________________</p>
            <label>Date:</label>
            @if($approvals->first())
                {{ \Carbon\Carbon::parse($approvals->first()->created_at)->format('d-m-Y') }}
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
            @if($approvals->first())
                {{ \Carbon\Carbon::parse($approvals->first()->updated_at)->format('d-m-Y') }}
            @endif
        </td>
    </tr>
</table>

</body>
</html>
