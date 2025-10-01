@extends('layouts.app')

@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')
<div class="content-wrapper">
<section class="content">
<div class="container">
    <h1>Reviewed Performance Summaries</h1>

    {{-- ================= User-Level Table ================= --}}
    <h2>User Ratings</h2>
    <table border="1" width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
        <thead style="background:#f1f1f1;">
            <tr>
                <th>User</th>
                <th>Department</th>
                <th>Section</th>
                <th>Self Rating</th>
                <th>Assessor Rating</th>
                <th>Reviewer Rating</th>
                <th>Period</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summaries as $summary)
            <tr>
                <td>{{ $summary->user->first_name }} {{ $summary->user->last_name }}</td>
                <td>{{ $summary->user->department ?? '-' }}</td>
                <td>{{ $summary->user->section ?? '-' }}</td>
                <td>{{ $summary->total_self_label }}</td>
                <td>{{ $summary->total_assessor_label }}</td>
                <td>{{ $summary->total_reviewer_label }}</td>
                <td>{{ $summary->period->name ?? $summary->period->year ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No reviewed performance summaries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= Department-Level Table ================= --}}
    <h2>Overall Department Performance</h2>
    <table border="1" width="50%" style="border-collapse: collapse; margin-bottom: 20px;">
        <thead style="background:#e6e6e6;">
            <tr>
                <th>Department</th>
                <th>Overall Assessor Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departmentGrades as $dept => $grade)
            <tr>
                <td>{{ $dept }}</td>
                <td>{{ $grade }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= Section-Level Table ================= --}}
    <h2>Overall Section Performance</h2>
    <table border="1" width="50%" style="border-collapse: collapse; margin-bottom: 20px;">
        <thead style="background:#e6e6e6;">
            <tr>
                <th>Section</th>
                <th>Overall Assessor Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sectionGrades as $section => $grade)
            <tr>
                <td>{{ $section }}</td>
                <td>{{ $grade }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= Organization-Level Table ================= --}}
    <h2>Organization Performance (Average Across Departments)</h2>
    @php
        $orgAvgNumeric = collect($departmentGradesNumeric)->avg();
        $orgAvgGrade = match(true){
            $orgAvgNumeric>=5.5=>'A1',
            $orgAvgNumeric>=4.5=>'A2',
            $orgAvgNumeric>=3.5=>'B1',
            $orgAvgNumeric>=2.5=>'B2',
            $orgAvgNumeric>=1.5=>'C1',
            $orgAvgNumeric>=0.5=>'C2',
            default => '-'
        };
    @endphp
    <table border="1" width="30%" style="border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <th>Organization Overall Rating</th>
            <td>{{ $orgAvgGrade }}</td>
        </tr>
    </table>

    {{-- ================= Charts ================= --}}
    <h2>Department Performance Chart</h2>
    <canvas id="deptChart" width="600" height="300"></canvas>

    <h2>Organization Performance Over Periods</h2>
    <canvas id="orgChart" width="600" height="300"></canvas>

</div>
</section>
</div>
</div>
@endsection

@section('scripts')

@endsection
