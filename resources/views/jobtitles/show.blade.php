

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show jobtitle</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('jobtitles.index') }}"> Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>jobtitle Name:</strong>
                {{ $jobtitle->jobtitle }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Deparment</strong>
                {{ $jobtitle->jobtitle }}
            </div>
        </div>
    </div>
@endsection
<p class="text-center text-primary"><small>Tutorial by LaravelTuts.com</small></p>