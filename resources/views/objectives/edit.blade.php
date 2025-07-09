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
    <h1>Edit objective</h1>
    </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('objectives.index') }}"> Back</a>
        </div>
    </div>
</div>

<div class="card card-default">
<div class="card-body">
    <form action="{{ route('objectives.update', $objective) }}" method="POST">
        @csrf
        @method('PUT') <!-- This indicates that this is a PUT request -->
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
        <label for="user_id">Select User:</label>
        <select name="user_id" class="form-control select2" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $user->id == $objective->user_id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        </div>   
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">

        <label for="objective">objective:</label>
        <input type="text" name="objective" value="{{ $objective->objective }}" class="form-control" required>

        </div>
            </div>   
        </div>
        <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
        <label for="period_id">Select Period:</label>
        <select name="period_id" class="form-control select2" required>
            @foreach ($periods as $period)
                <option value="{{ $period->id }}" {{ $period->id == $objective->period_id ? 'selected' : '' }}>
                    {{ $period->year }}
                </option>
            @endforeach
        </select>

        </div>
            </div>  
        
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
        <label for="target_id">Select Target:</label>
        <select name="target_id" class="form-control select2" required>
            @foreach ($targets as $target)
                <option value="{{ $target->id }}" {{ $target->id == $objective->target_id ? 'selected' : '' }}>
                    {{ $target->target_name }}
                </option>
            @endforeach
        </select>

        </div>
            </div>  
        
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</section>
</div>
</div>
@endsection