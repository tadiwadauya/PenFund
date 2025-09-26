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
            text-align: center;
        }
        .signature-block {
            margin-top: 40px;
            width: 100%;
        }
        .signature-line {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            gap: 20px; /* space between fields */
        }
        .signature-line label {
            font-weight: bold;
            margin-right: 5px;
        }
        .signature-line span {
            border-bottom: 1px solid #000;
            min-width: 150px;
            display: inline-block;
            padding: 2px 5px;
        }
        .signature-date {
            margin-left: 0;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<center>
    <h3>{{ strtoupper($user->department) }} DEPARTMENT</h3>
    <h3>{{ strtoupper($user->jobtitle) }} - PERFORMANCE TARGETS FOR THE PERIOD JAN – DEC {{ $period->year }}</h3>
</center>

{{-- One table for all objectives/initiatives/targets --}}
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>KEY TASK</th>
            <th>OBJECTIVE</th>
            <th>TASK</th>
            <th>TARGET</th>
        </tr>
    </thead>
    <tbody>
        @forelse($objectives as $index => $objective)
            @php
                $initiativeCount = $objective->initiatives->count() ?: 1;
            @endphp
            @foreach($objective->initiatives as $i => $initiative)
                <tr>
                    @if($i === 0)
                        <td rowspan="{{ $initiativeCount }}">{{ $index + 1 }}</td>
                        <td rowspan="{{ $initiativeCount }}">{{ $objective->target->target_name ?? 'No Target' }}</td>
                        <td rowspan="{{ $initiativeCount }}">{{ $objective->objective }}</td>
                    @endif
                    <td >{{ $initiative->initiative }}</td>
                    <td >{{ $initiative->budget }}</td>
                </tr>
            @endforeach
            @if($objective->initiatives->isEmpty())
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $objective->target->target_name ?? 'No Target' }}</td>
                    <td>{{ $objective->objective }}</td>
                    <td colspan="2">No initiatives added</td>
                </tr>
            @endif
        @empty
            <tr>
                <td colspan="5"><em>No objectives available for this period.</em></td>
            </tr>
        @endforelse
    </tbody>
</table>





{{-- Signatures --}}
<div class="signature-block">

    {{-- Incumbent --}}
    <div class="signature-line">
        <label>Incumbent Name:</label>
        <span>{{ $user->first_name }} {{ $user->last_name }}</span>

        <label>Signature:</label>
        <span>{{ $user->name }}</span>
    </div>
    <div class="signature-date">
        <label>Date:</label>
        <span>
            @if($approvals->first())
                {{ \Carbon\Carbon::parse($approvals->first()->created_at)->format('d-m-Y') }}
            @endif
        </span>
    </div>

    {{-- Superior --}}
    <div class="signature-line" style="margin-top:20px;">
        <label>Human Resources Officer:</label>
        @if($approvals->first())
            <span>Shadreck Chigango</span>

        <label>Signature:</label>
        <span>schigango</span>   @endif
    </div>
    <div class="signature-date">
        <label>Date:</label>
        <span>
            @if($approvals->first())
                {{ \Carbon\Carbon::parse($approvals->first()->updated_at)->format('d-m-Y') }}
            @endif
        </span>
    </div>

</div>

</body>
</html>
