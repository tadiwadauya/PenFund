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
        <div class="pull-left">
        <h2>Periods</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('periods.index') }}"> Back</a>
        </div>
    </div>
</div>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('periods.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
        <label for="year">Year:</label>
        <input type="text"  class="form-control" name="year" id="year" placeholder="Enter year (e.g., 2025)" pattern="\d{4}" required>
                </div>
            </div> 
          </div>
        <button type="submit"  class="btn btn-primary">Create</button>
    </form>


    </div>
</section>
</div>
</div>
@endsection