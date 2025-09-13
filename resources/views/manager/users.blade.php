@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>Manage Department Perfomance Targets</h1>

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
                        <a href="{{ route('manager.users.show', $mUser->id) }}" class="btn btn-info">View</a>
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
