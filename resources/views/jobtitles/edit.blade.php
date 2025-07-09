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
                <h2>Edit JobTitle</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('jobtitles.index') }}"> Back</a>
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
    <form action="{{ route('jobtitles.update',$jobtitle->id) }}" method="POST">
        @csrf
        @method('PUT')
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>JobTitle Name:</strong>
                    <input type="text" name="jobtitle" value="{{ $jobtitle->jobtitle }}" class="form-control" placeholder="jobtitle name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
    <strong>Department:</strong>
    <select name="department" class="form-control">
        <option value="">Select Department</option>
        @foreach($departments as $department)
            <option value="{{ $department->department }}" {{ $department->department == $jobtitle->department ? 'selected' : '' }}>
                {{ $department->department }}
            </option>
        @endforeach
    </select>
</div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Section:</strong>
                    <select name="department" class="form-control">
        <option value="">Select Section</option>
        @foreach($sections as $section)
            <option value="{{ $section->section }}" {{ $section->section == $jobtitle->section ? 'selected' : '' }}>
                {{ $section->section }}
            </option>
        @endforeach
    </select>
                    </div>
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