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
            <h2>Edit New User</h2>
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
{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
<div class="row">
 <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>UserName:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>first_name:</strong>
            {!! Form::text('first_name', null, array('placeholder' => 'first_name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>last_name:</strong>
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
                                <option value="{{ $department->department }}" {{ $user->department == $department->department ? 'selected' : '' }}>
                                    {{ $department->department }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>


    <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Section:</strong>
                    <select class="form-control select2" name="section" id="section">
                        <option value="">Select Section</option>
                        @if ($sections)
                            @foreach($sections as $section)
                                <option value="{{ $section->section }}" {{ $user->section == $section->section ? 'selected' : '' }}>
                                    {{ $section->section }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Job Title:</strong>
                    <select class="form-control select2" name="jobtitle" id="jobtitle" >
                        <option value="">Select Job Title</option>
                        @if ($jobtitles)
                            @foreach($jobtitles as $jobtitle)
                                <option value="{{ $jobtitle->jobtitle }}" {{ $user->jobtitle == $jobtitle->jobtitle ? 'selected' : '' }}>
                                    {{ $jobtitle->jobtitle }}
                                </option>
                            @endforeach
                        @endif
                    </select>
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
            {!! Form::text('grade', null, array('placeholder' => 'grade','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Supervisor:</strong>
        <select class="form-control select2" name="supervisor_id">
            <option value="">Select Supervisor</option>
            @foreach($users as $supervisor)
                <option value="{{ $supervisor->id }}" {{ $user->supervisor_id == $supervisor->id ? 'selected' : '' }}>
                    {{ $supervisor->first_name }} {{ $supervisor->last_name }} ({{ $supervisor->name }})
                </option>
            @endforeach
        </select>
    </div>
</div>

    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>mobile:</strong>
            {!! Form::text('address', null, array('placeholder' => 'address','class' => 'form-control')) !!}
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
        <strong>Gender:</strong>
        {!! Form::select(
            'gender',
            ['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female', 'other' => 'Other'],
            old('gender', $user->gender),
            ['class' => 'form-control']
        ) !!}
    </div>
</div>


<div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
        <strong>Date of Birth:</strong>
        {!! Form::date('dob', null, ['class' => 'form-control', 'placeholder' => 'Date of Birth']) !!}
    </div>
</div>
 <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2">
        <div class="form-group">
            <strong>Role:</strong>
            
            {!! Form::select('is_admin', ['' => 'Select Role', '1' => 'Admin', '0' => 'Normal User'], null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <input type="checkbox" id="changePasswordToggle" onclick="togglePasswordFields()">
        <label for="changePasswordToggle"><strong>Change Password</strong></label>
    </div>
</div>
<div id="passwordFields" style="display:none;">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <strong>Password:</strong>
                {!! Form::password('password', [
                    'placeholder' => 'Password',
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
                    'value' => ''
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <strong>Confirm Password:</strong>
                {!! Form::password('confirm-password', [
                    'placeholder' => 'Confirm Password',
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
                    'value' => ''
                ]) !!}
            </div>
        </div>
    </div>
</div>





    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}  

</div>
</section>
</div>
</div>

<script>
function togglePasswordFields() {
    var checkBox = document.getElementById("changePasswordToggle");
    var passwordFields = document.getElementById("passwordFields");
    passwordFields.style.display = checkBox.checked ? "block" : "none";
}
</script>
<script>
    function filterJobsTitles() {
        var departmentSelect = document.getElementById("department");
        var jobTitleSelect = document.getElementById("jobtitle");
        var selectedDepartment = departmentSelect.value;

        // Show all job titles initially
        for (var i = 0; i < jobTitleSelect.options.length; i++) {
            var option = jobTitleSelect.options[i];
            if (selectedDepartment === "" || option.getAttribute("data-department") === selectedDepartment) {
                option.style.display = "block"; // Show the option
            } else {
                option.style.display = "none"; // Hide the option
            }
        }

        // Reset the job title selection
        jobTitleSelect.value = "";
    }
</script>
@endsection
