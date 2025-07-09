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
    <h1>All Actions To Support Objectives</h1>
    </div>
    <div class="pull-right">
    <a href="{{ route('initiatives.create') }}" class="btn btn-primary  ml-auto">Create New Action</a>
    
    </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($initiatives->isEmpty())
        <p>No initiatives found.</p>
    @else
        <table  id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Target</th>
                    <th>Objective</th>
                    <th>Action</th>
                    <th>Budget/Action</th>
                    <th>Employee</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($initiatives as $initiative)
                    <tr>
                        <td>{{ $initiative->period->year }}</td>
                        <td>{{ $initiative->target->target_name }}</td>
                        <td>{{ $initiative->objective->objective }}</td>
                        <td>{{ $initiative->initiative }}</td>
                        <td>{{ $initiative->budget }}</td>
                        <td>{{ $initiative->user->name }}</td>
                        <td>
                            <a href="{{ route('initiatives.show', $initiative) }}" class="btn btn-info">View</a>
                            <a href="{{ route('initiatives.edit', $initiative) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('initiatives.destroy', $initiative) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this initiative?');">Delete</button>
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