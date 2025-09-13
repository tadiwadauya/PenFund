@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

<a href="{{ route('mypurpose.create') }}" class="btn btn-primary ml-auto">Add Purpose</a>
<a href="{{ route('periods.create') }}" class="btn btn-primary ml-auto">Add Period</a>
    <h1>My Performance Periods</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Period</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($periods as $period)
                @php
                    $approval = auth()->user()->approvals()->where('period_id', $period->id)->first();
                @endphp
                <tr>
                    <td>{{ $period->year }}</td>
                    <td>{{ $approval->status ?? 'Not Submitted' }}</td>
                    <td>
                        <a href="{{ route('user.performance.show', $period->id) }}" class="btn btn-info">My Perfomance Target</a>
                        @if(!$approval)
                            <form method="POST" action="{{ route('user.performance.submit', $period->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">Submit for Approval</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
</section>
</div>
</div>
@endsection
