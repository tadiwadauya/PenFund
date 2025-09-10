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
                            <h1>All Purposes</h1>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('mypurpose.create') }}" class="btn btn-primary ml-auto">Create New Purpose</a>
                            <a href="{{ url('myobjective') }}" class="btn btn-info ml-auto">My Objectives</a>
                            <a href="{{ url('myinitiative') }}" class="btn btn-info  ml-auto">Actions to support objectives</a>
                            <a href="{{ url('myreport') }}" class="btn btn-info ml-auto">My Perfomance Target Report</a>
                        </div>
                    </div>
                </div>
                
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($purposes->isEmpty())
                    <p>No purposes found.</p>
                @else
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Purpose</th>
                                <th>Period</th>
                                <th>User</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purposes as $purpose)
                                <tr>
                                    <td>{{ $purpose->purpose }}</td>
                                    <td>{{ $purpose->period->year }}</td>
                                    <td>{{ $purpose->user->name }}</td>
                                    <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('mypurpose.show', $purpose) }}" class="btn btn-info">View</a>
                                        <a href="{{ route('purposes.edit', $purpose) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('mypurpose.destroy', $purpose) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this purpose?');">Delete</button>
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