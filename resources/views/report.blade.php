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
        <a href="{{ url('objectives') }}" class="btn btn-info  ml-auto">Objectives</a>
    <a href="{{ url('purposes') }}" class="btn btn-info ml-auto">Purpose</a>
    <a href="{{ url('report') }}" class="btn btn-info ml-auto">Perfomance Target Report</a>
            <div class="pull-left">
    <h1>Generate Report</h1>
    </div>
    
    </div>
    </div>
   
    <form action="{{ route('report.generate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
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