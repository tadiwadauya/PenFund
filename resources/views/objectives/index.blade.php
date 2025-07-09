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
    <h1>All Objectives</h1>
    </div>
    <div class="pull-right">
    <a href="{{ route('objectives.create') }}" class="btn btn-primary  ml-auto">Create New Objective</a>
    
    </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($objectives->isEmpty())
        <p>No objectives found.</p>
    @else
        <table  id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Target</th>
                    <th>Objective</th>
                    <th>User</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($objectives as $objective)
                    <tr>
                        <td>{{ $objective->period->year }}</td>
                        <td>{{ $objective->target->target_name }}</td>
                        <td>{{ $objective->objective }}</td>
                        <td>{{ $objective->user->name }}</td>
                        <td>{{ $objective->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('objectives.show', $objective) }}" class="btn btn-info">View</a>
                            <a href="{{ route('objectives.edit', $objective) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('objectives.destroy', $objective) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this objective?');">Delete</button>
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