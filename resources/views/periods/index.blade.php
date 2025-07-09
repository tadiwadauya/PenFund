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
    <h1>Periods</h1>
    </div>
    <div class="pull-right">
    <a class="btn btn-primary  ml-auto" href="{{ route('periods.create') }}">Create Period</a>
       
    </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="example1" class="table table-bordered table-striped">
       
        <thead>
            <tr>
                <th>Year</th>
                <th width="280px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($periods as $period)
                <tr>
                    <td>{{ $period->year }}</td>
                    <td>
                    <a class="btn btn-info"href="{{ route('periods.show', $period->id) }}">Show</a>
                        <a  class="btn btn-primary" href="{{ route('periods.edit', $period->id) }}">Edit</a>
                        <form action="{{ route('periods.destroy', $period->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
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
