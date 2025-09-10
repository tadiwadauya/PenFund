@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')

<!-- /.navbar -->

<!-- Main Sidebar Container -->
@include('includes.sidebar')
<div class="content-wrapper">
<section class="content">
<div class="container-fluid">
    
    <div class="row">
        <div class="col-lg-12 margin-tb">
        <br>
        <a href="{{ url('myobjective') }}" class="btn btn-info  ml-auto">My Objectives</a>
        <a href="{{ url('purposes/mypurpose') }}" class="btn btn-info ml-auto">My Purpose</a>
        <a href="{{ url('myinitiative') }}" class="btn btn-info ml-auto">Action to Support Objectives</a>
            <div class="pull-left">
    <h1>Generate Perfomance Target</h1>
    </div>
    
    </div>
    </div>
   
    <form action="{{ route('report.generate') }}" method="POST">
        @csrf
        <div class="form-group">
    <label for="user_id">User:</label>
    <select id="user_id" class="form-control" disabled>
        <option value="{{ auth()->user()->id }}" selected>
            {{ auth()->user()->name }}
        </option>
    </select>

    <!-- Hidden input to actually submit the authenticated user ID -->
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
</div>

        <div class="form-group">
            <label for="period_id">Select Period:</label>
            <select name="period_id" id="period_id" class="form-control">
                @foreach($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->year }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generate PDF</button>
    </form>
</div>
</div>
</section>
</div>
</div>
@endsection