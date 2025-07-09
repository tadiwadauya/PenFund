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
                <h2>JobTitle</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('jobtitles.create') }}"> Create New JobTitle</a>
              
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>JobTitle</th>
            <th>Deparment</th>
            <th>Section</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($jobtitles as $jobtitle)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $jobtitle->jobtitle }}</td>
         <td>{{ $jobtitle->department }}</td>
         <td>{{ $jobtitle->section }}</td>
        </td></td>
            <td>
                <form action="{{ route('jobtitles.destroy',$jobtitle->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('jobtitles.show',$jobtitle->id) }}">Show</a>
                  
                    <a class="btn btn-primary" href="{{ route('jobtitles.edit',$jobtitle->id) }}">Edit</a>
                   
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
               
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $jobtitles->links() !!}

    </div>
</section>
</div>
</div>
@endsection