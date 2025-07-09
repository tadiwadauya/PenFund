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
    <h1>Action to support {{ $initiative->objective->objective }}  Details</h1>
    </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('initiatives.index') }}"> Back</a>
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

    <p><strong>Action to support objective:</strong> {{ $initiative->initiative }}</p>
    <p><strong>Objective:</strong> {{ $initiative->objective->objective }}</p>
    <p><strong>Period:</strong> {{ $initiative->period->year }}</p>
    <p><strong>Target:</strong> {{ $initiative->target->target_name }}</p>
    <p><strong>Created At:</strong> {{ $initiative->created_at }}</p>
    <p><strong>Updated At:</strong> {{ $initiative->updated_at }}</p>

   
    <a href="{{ route('initiatives.edit', $initiative) }}" class="btn btn-info">Edit Action</a>

    <form action="{{ route('initiatives.destroy', $initiative) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"  onclick="return confirm('Are you sure you want to delete this initiative?');">Delete Action</button>
    </form>
    </div>
</section>
</div>
</div>
</div>
</div>
@endsection