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
            <a class="btn btn-primary" href="{{ route('mypurpose.index') }}"> Back</a>
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
<form action="{{ route('mypurpose.store') }}" method="POST">
        @csrf
        <div class="form-group">
        <div class="card-body">
              <textarea id="purpose" name="purpose"></textarea>
            </div>
        <div class="form-group">
            <label for="period_id">Period</label>
            <select class="form-control" name="period_id" id="period_id" required>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->year }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Purpose</button>
    </form>
    </div>
</section>
</div>
</div>
</div>
</div>
@endsection
