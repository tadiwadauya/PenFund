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
    <h1>Targets</h1>
    </div>
    <div class="pull-right">
    <a class="btn btn-primary  ml-auto" href="{{ route('targets.create') }}">Create Target</a>
      
    </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Target</th>
            <th>User</th>
            <th>Period</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($targets as $target)
            <tr>
                <td>{{ $target->target_name }}</td>
                <td>{{ $target->user ? $target->user->first_name . ' ' . $target->user->last_name : '-' }}</td>
                <td>{{ $target->period ? $target->period->year : '-' }}</td>
                <td>
                    <a href="{{ route('targets.show', $target->id) }}" class="btn btn-sm btn-info">Show</a>
                    <a href="{{ route('targets.edit', $target->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('targets.destroy', $target->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this target?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No targets found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</div>
</section>
</div>
</div>
@endsection
