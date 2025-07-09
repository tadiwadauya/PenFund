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

    <table id="example1" class="table table-bordered table-striped">
       
        <thead>
            <tr>
                <th>Target Name</th>
                <th width="280px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($targets as $target)
                <tr>
                    <td>{{ $target->target_name }}</td>
                    <td>
                        <a class="btn btn-info" href="{{ route('targets.show', $target->id) }}">Show</a>
                        <a class="btn btn-primary" href="{{ route('targets.edit', $target->id) }}">Edit</a>
                        <form action="{{ route('targets.destroy', $target->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this purpose?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</section>
</div>
</div>
@endsection
