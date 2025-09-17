@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>My Performance Appraisal Periods</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Period</th>
               
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($periods as $period)
                @php
                    // Fetch approval for this user/period
                    $approval = auth()->user()->approvals()->where('period_id', $period->id)->first();

                    // Fetch authorisation for this user/period
                    $authorisation = auth()->user()->authorisations()->where('period_id', $period->id)->first();
                   
                @endphp
                <tr>
                    <td>{{ $period->year }}</td>

                   

                    <td>
                        <a href="{{ route('user.performanceapraisal.show', $period->id) }}" class="btn btn-info">My Performance Appraisal</a>

                        @if(!$authorisation)
                            <form method="POST" action="{{ route('user.performanceapraisal.submit', $period->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">Submit for Authorisation</button>
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
