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
    <h1>My objectives</h1>
    </div>
    <div class="pull-right">
    <a href="{{ route('objectives.create') }}" class="btn btn-primary  ml-auto">Create New objective</a>
    <a href="{{ url('myinitiative') }}" class="btn btn-info  ml-auto">Actions to support objectives</a>
    <a href="{{ url('purposes/mypurpose') }}" class="btn btn-primary ml-auto">My Purpose</a>
    
    </div>
    
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($objectives->isEmpty())
        <div class="alert alert-info">
            <p>You have no objectives to display.</p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Objective</th>
                    <th>Target</th>
                    <th>Period</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($objectives as $objective)
                    <tr>
                        <td>{{ $objective->id }}</td>
                        <td>{{ $objective->objective }}</td>
                        <td>{{ $objective->target->target_name }}</td>
                        <td>{{ $objective->period->year }}</td>
                        <td>{{ $objective->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('objectives.edit', $objective->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</section>
</div>
</div>
@endsection