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
                <h2>Add New JobTitle</h2>
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
    <form action="{{ route('jobtitles.store') }}" method="POST">
        @csrf
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>jobtitle Name:</strong>
                    <input type="text" name="jobtitle" class="form-control" placeholder="e.g. Accountant">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                <strong>Section:</strong>
                <select class="form-control select2" name="section" id="section">
                                        <option value="">Select Section</option>

                                        @if ($sections)
                                            @foreach($sections as $section)
                                                <option value="{{ $section->section }} ">{{ $section->section }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                    </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                <strong>Deparment:</strong>
                <select class="form-control select2" name="department" id="department">
                                        <option value="">Select Deparment</option>

                                        @if ($departments)
                                            @foreach($departments as $department)
                                                <option value="{{ $department->department }} ">{{ $department->department }}</option>
                                            @endforeach
                                        @endif

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