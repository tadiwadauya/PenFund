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
            <h1>Create Purpose</h1>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('purposes.index') }}"> Back</a>
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
    <form action="{{ route('purposes.storeMyCreate') }}" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <input type="text" name="purpose" class="form-control" required>
            </div>
        </div>   
    </div>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label for="period_id">Select Period:</label>
                <select name="period_id" class="form-control select2" required>
                    @foreach ($periods as $period)
                        <option value="{{ $period->id }}">{{ $period->year }}</option>
                    @endforeach
                </select>
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