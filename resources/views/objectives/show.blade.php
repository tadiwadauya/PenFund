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
    <h1>objective Details</h1>
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

    <p><strong>objective:</strong> {{ $objective->objective }}</p>
    <p><strong>Period:</strong> {{ $objective->period->year }}</p>
    <p><strong>Target:</strong> {{ $objective->target->target_name }}</p>
    <p><strong>Created At:</strong> {{ $objective->created_at }}</p>
    <p><strong>Updated At:</strong> {{ $objective->updated_at }}</p>

   
    <a href="{{ route('objectives.edit', $objective) }}" class="btn btn-info">Edit objective</a>

    <form action="{{ route('objectives.destroy', $objective) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"  onclick="return confirm('Are you sure you want to delete this objective?');">Delete objective</button>
    </form>
    </div>
</section>
</div>
</div>
</div>
</div>
@endsection