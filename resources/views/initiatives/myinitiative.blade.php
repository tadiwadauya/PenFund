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
    <h1>My initiatives</h1>
    </div>
    <div class="pull-right">
    <a href="{{ route('initiatives.create') }}" class="btn btn-primary  ml-auto">Create New initiative</a>
    <a href="{{ route('initiatives.create') }}" class="btn btn-info  ml-auto">Download Contract</a>
    
    </div>
    
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($initiatives->isEmpty())
        <div class="alert alert-info">
            <p>You have no initiatives to display.</p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>initiative</th>
                    <th>Target</th>
                    <th>Objective</th>
                    <th>Period</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($initiatives as $initiative)
                    <tr>
                        <td>{{ $initiative->id }}</td>
                        <td>{{ $initiative->initiative }}</td>
                        <td>{{ $initiative->target->target_name }}</td>
                        <td>{{ $initiative->objective->objective }}</td>
                        <td>{{ $initiative->period->year }}</td>
                        <td>{{ $initiative->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('initiatives.edit', $initiative->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('initiatives.destroy', $initiative->id) }}" method="POST" style="display:inline;">
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