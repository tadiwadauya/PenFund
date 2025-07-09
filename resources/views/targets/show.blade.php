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
    <h1>Target Details</h1>
</div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('targets.index') }}"> Back</a>
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

<div class="card card-default">
<div class="card-body">

    <p><strong>Target Name:</strong> {{ $target->target_name }}</p>
    <a href="{{ route('targets.index') }}" class="btn btn-primary">Back to Targets</a>

    </section>
</div>
</div>
</div>
</div>
    @endsection