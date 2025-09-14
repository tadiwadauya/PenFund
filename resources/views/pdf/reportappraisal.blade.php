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
    <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
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
            <ul>
                @foreach($purposes as $purpose)
                    <li>{{ $purpose->purpose }}</li>
                @endforeach
            </ul>
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

{{-- Loop objectives by target --}}
@foreach($objectives as $objective)
    <h3>{{ $objective->target->target_name ?? 'No Target' }}</h3>
    <table>
        <tr>
            <th>Objective</th>
            <th>Action / Initiative</th>
            <th>Target / Budget</th>
            <th>Achieved</th>
            <th>Appraisal Rating</th>
            <th>Comment</th>
        </tr>
        @forelse($objective->initiatives as $initiative)
            <tr>
                <td>{{ $objective->objective }}</td>
                <td>{{ $initiative->initiative }}</td>
                <td>{{ $initiative->budget }}</td>
                <td>{{ $initiative->archieved ? 'Yes' : 'No' }}</td>
                <td>
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
                    {{ $labels[$initiative->rating] ?? 'Not Rated' }}
                </td>
                <td>{{ $initiative->comment }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No initiatives added.</td>
            </tr>
        @endforelse
    </table>

    {{-- Display target average rating --}}
    @php
        $targetAvg = $objective->initiatives->avg('rating');
        $targetLabel = $labels[$targetAvg] ?? 'Not Rated';
    @endphp
    <p class="average-rating">
        Average Rating for {{ $objective->target->target_name ?? 'No Target' }}: {{ $targetLabel }}
    </p>

@endforeach

{{-- Overall rating --}}
@php
    $overallAvg = $objectives->flatMap->initiatives->avg('rating');
    $overallLabel = $labels[$overallAvg] ?? 'Not Rated';
@endphp

<h4>Overall Performance Rating</h4>
<table>
    <tr>
        <th>Overall Rating</th>
    </tr>
    <tr>
        <td>{{ $overallLabel }}</td>
    </tr>
</table>

</body>
</html>
