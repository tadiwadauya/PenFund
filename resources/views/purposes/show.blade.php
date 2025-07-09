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
    <h1>Purpose Details</h1>
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

    <p><strong>Purpose:</strong> {{ $purpose->purpose }}</p>
    <p><strong>Period:</strong> {{ $purpose->period->year }}</p>
    <p><strong>Created At:</strong> {{ $purpose->created_at }}</p>
    <p><strong>Updated At:</strong> {{ $purpose->updated_at }}</p>

   
    <a href="{{ route('purposes.edit', $purpose) }}" class="btn btn-info">Edit Purpose</a>

    <form action="{{ route('purposes.destroy', $purpose) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-primary">Delete Purpose</button>
    </form>
    </div>
</section>
</div>
</div>
</div>
</div>
@endsection