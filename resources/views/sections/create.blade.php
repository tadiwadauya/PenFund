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
                <h2>Add New Section</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('sections.index') }}"> Back</a>
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
    <form action="{{ route('sections.store') }}" method="POST">
        @csrf
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Section Name:</strong>
                    <input type="text" name="section" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                <strong>Department:</strong>
        <select class="form-control select2" name="department" id="department"> <!-- Changed name to 'department' -->
            <option value="">Select Department</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->department }}</option>
            @endforeach
        </select>
                    </div>
            </div>
</div>    
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                <strong>Manager:</strong>
                <select class="form-control select2" name="manager" id="manager">
                                        <option value="">Select Manager</option>

                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }}) - {{ $user->paynumber }}</option>
                                        @endforeach

                                    </select>
                    </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Assistant Manager:</strong>
                    <select class="form-control select2" name="asst_manager" id="asst_manager">
                                        <option value="">Select Manager</option>

                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }}) - {{ $user->paynumber }}</option>
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
</div>
@endsection