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
                <h2>Edit department</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('departments.index') }}"> Back</a>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('departments.update',$department->id) }}" method="POST">
        @csrf
        @method('PUT')
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Department Name:</strong>
                    <input type="text" name="department" value="{{ $department->department }}" class="form-control" placeholder="department name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
    <strong>Manager:</strong>
    <select name="manager" class="form-control">
        <option value="">Select Manager</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $user->id == $department->manager ? 'selected' : '' }}>
                {{ $user->first_name }} {{ $user->last_name }}
            </option>
        @endforeach
    </select>
</div>

            <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
    <strong>Assistant Manager:</strong>
    <select name="asst_manager" class="form-control">
        <option value="">Select Assistant Manager</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $user->id == $department->asst_manager ? 'selected' : '' }}>
                {{ $user->first_name }} {{ $user->last_name }}
            </option>
        @endforeach
    </select>
</div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

</div>
</section>
</div>
</div>@endsection