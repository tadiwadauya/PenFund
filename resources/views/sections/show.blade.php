@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Section</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('sections.index') }}"> Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Section Name:</strong>
                {{ $section->section }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Department:</strong>
                @if(isset($users[$section->department]))
                {{ $departments[$section->department]->department }} 
            @else
                Not Assigned
            @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Manager:</strong>
                @if(isset($users[$section->manager]))
                {{ $users[$section->manager]->first_name }} - {{ $users[$section->manager]->last_name }}
            @else
                Not Assigned
            @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Assistant Manager:</strong>
                @if(isset($users[$section->asst_manager]))
                {{ $users[$section->asst_manager]->first_name }} - {{ $users[$section->asst_manager]->last_name }}
            @else
                Not Assigned
            @endif
            </div>
        </div>
    </div>
@endsection
<p class="text-center text-primary"><small>Tutorial by LaravelTuts.com</small></p>


