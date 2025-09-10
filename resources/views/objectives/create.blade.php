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
    <h1>Create objective</h1>
    </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('objectives.index') }}"> Back</a>
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
    <form action="{{ route('objectives.store') }}" method="POST">
        @csrf
        <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <label for="user_id">User:</label>
        <select id="user_id" class="form-control select2" disabled>
            <option value="{{ auth()->user()->id }}" selected>
                {{ auth()->user()->name }}
            </option>
        </select>
        <!-- Hidden input so the authenticated user_id is actually submitted -->
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    </div>   
</div>

    
    <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
        <label for="target_id">Select Target:</label>
        <select name="target_id" class="form-control select2"  required>
            @foreach ($targets as $target)
                <option value="{{ $target->id }}">{{ $target->target_name }}</option>
            @endforeach
        </select>
        </div>
            </div> 

    </div>

        <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
        <label for="period_id">Select Period:</label>
        <select name="period_id" class="form-control select2"  required>
            @foreach ($periods as $period)
                <option value="{{ $period->id }}">{{ $period->year }}</option>
            @endforeach
        </select>
        </div>
            </div>   
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
        <label for="objective">objective:</label>
        <input type="text" name="objective" class="form-control"  required>
        </div>
            </div>   
        </div>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    </div>
</section>
</div>
</div>
</div>
</div>
@endsection