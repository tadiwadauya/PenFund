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
            <h2>Create New User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
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
{!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
<div class="row">
<div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>UserName:</strong>
        {!! Form::text('name', null, array('placeholder' => 'User Name', 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'display:none;')) !!}
        {!! Form::text('name', null, array('placeholder' => 'User Name', 'class' => 'form-control', 'autocomplete' => 'off', 'name' => 'name')) !!}
    </div>
</div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::email('email', null, array('placeholder' => 'email','class' => 'form-control', 'autocomplete' => 'off', 'style' => 'display:none;')) !!}
            {!! Form::email('email', null, array('placeholder' => 'email', 'class' => 'form-control', 'autocomplete' => 'off', 'name' => 'email')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>First Name:</strong>
            {!! Form::text('first_name', null, array('placeholder' => 'first name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Last Name:</strong>
            {!! Form::text('last_name', null, array('placeholder' => 'last_name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Department:</strong>
        <select class="form-control select2" name="department" id="department">
            <option value="">Select Department</option>
            @if ($departments)
                @foreach($departments as $department)
                    <option value="{{ $department->department }}">{{ $department->department }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Job Title:</strong>
        <select class="form-control select2" name="jobtitle" id="jobtitle">
            <option value="">Select Job Title</option>
            @if ($jobtitles)
                @foreach($jobtitles as $jobtitle)
                    <option value="{{ $jobtitle->jobtitle }}">{{ $jobtitle->jobtitle }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>



    <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
            <strong>Section:</strong>
            <select class="form-control select2" name="section" id="section" >
            <option value="">Select Section</option>
            @if ($sections)
                @foreach($sections as $section)
                    <option value="{{ $section->section }}">{{ $section->section }}</option>
                @endforeach
            @endif
        </select>
        </div>
       
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>mobile:</strong>
            {!! Form::text('mobile', null, array('placeholder' => 'mobile','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>extension:</strong>
            {!! Form::text('extension', null, array('placeholder' => 'extension','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
            <strong>Address:</strong>
            {!! Form::text('address', null, array('placeholder' => 'address','class' => 'form-control')) !!}
        </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Grade:</strong>
            {!! Form::number('grade', null, array('placeholder' => 'grade','class' => 'form-control')) !!}
        </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
    <strong>Supervisor:</strong>
    <select class="form-control select2" name="supervisor_id">
        <option value="">Select Supervisor</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->name }})</option>
        @endforeach
    </select>
</div>

    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Gender:</strong>
        {!! Form::select('gender', ['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female', 'other' => 'Other'], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Date of Birth:</strong>
        {!! Form::date('dob', null, ['class' => 'form-control', 'placeholder' => 'Date of Birth']) !!}
    </div>
</div>
<div class="col-xs-2 col-sm-2 col-md-2">
    <div class="form-group">
        <strong>Role:</strong> {!! Form::select('is_admin', ['' => 'Select Role', '1' => 'Admin', '0' => 'User'], null, ['class' => 'form-control']) !!}
    </div>
</div>

    <div class="col-xs-5 col-sm-5 col-md-5">
    <div class="form-group">
        <strong>Password:</strong>
        {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control', 'oninput' => 'checkPasswordMatch()','autocomplete' => 'new-password']) !!}
    </div>
</div>
<div class="col-xs-5 col-sm-5 col-md-5">
    <div class="form-group">
        <strong>Confirm Password:</strong>
        {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control', 'required', 'oninput' => 'checkPasswordMatch()']) !!}
    </div>
</div>

<div id="password-message" style="color: red; display: none;">Passwords do not match.</div>

    
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}

  

</div>
</section>
</div>
</div>@endsection