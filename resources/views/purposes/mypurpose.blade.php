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
    <h1>My Purposes</h1>
    </div>
    <div class="pull-right">
    <a href="{{ route('purposes.create') }}" class="btn btn-primary  ml-auto">Create New Purpose</a>
    <a href="{{ route('purposes.create') }}" class="btn btn-info  ml-auto">Target</a>
    
    </div>
    
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($purposes->isEmpty())
        <div class="alert alert-info">
            <p>You have no purposes to display.</p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Purpose</th>
                    <th>Period</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purposes as $purpose)
                    <tr>
                        <td>{{ $purpose->id }}</td>
                        <td>{{ $purpose->purpose }}</td>
                        <td>{{ $purpose->period->year }}</td>
                        <td>{{ $purpose->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('purposes.edit', $purpose->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('purposes.destroy', $purpose->id) }}" method="POST" style="display:inline;">
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