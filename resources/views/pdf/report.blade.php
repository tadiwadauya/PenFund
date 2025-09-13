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
    <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
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
            <p><strong>Superior Position:</strong> {{ $managerJobTitle ?? 'N/A' }}</p>
            <p><strong>Superior Job Grade:</strong> {{ $managerGrade ?? 'N/A' }}</p>
        </td>
    </tr>
</table>

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

</body>
</html>
