@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show department</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('departments.index') }}"> Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Department Name:</strong>
                {{ $department->department }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Manager:</strong>
                @if(isset($users[$department->manager]))
                {{ $users[$department->manager]->first_name }} - {{ $users[$department->manager]->last_name }}
            @else
                Not Assigned
            @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Assistant Manager:</strong>
                @if(isset($users[$department->asst_manager]))
                {{ $users[$department->asst_manager]->first_name }} - {{ $users[$department->asst_manager]->last_name }}
            @else
                Not Assigned
            @endif
            </div>
        </div>
    </div>
@endsection
<p class="text-center text-primary"><small>Tutorial by LaravelTuts.com</small></p>


