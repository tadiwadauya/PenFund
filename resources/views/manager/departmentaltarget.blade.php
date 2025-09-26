@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>Finalised Tagets</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Section</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($managedUsers as $mUser)
                <tr>
                    <td>{{ $mUser->name }}</td>
                    <td>{{ $mUser->department }}</td>
                    <td>{{ $mUser->section }}</td>
                    <td>
                    <a href="{{ route('manager.user.target', [$mUser->id, $period->id]) }}" class="btn btn-info">View</a>

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
