@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')

<!-- /.navbar -->

<!-- Main Sidebar Container -->
@include('includes.sidebar')
<div class="content-wrapper">
<div class="content-header">
    <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   
                </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/hr') }}">HR Dashbord</a></li>
                        <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->

        <div class="card-header">
                <h3 class="card-title">
                 Showing All Users
                </h3>
            <div class="card-tools">
                <a class="btn btn-danger " href="{{ url('/users/create') }}">
                <i class="fa fa-fw fa-user" aria-hidden="true"></i>
                Deleted Users
                </a>                
            </div>
            <div class="card-tools">
                <a class="btn btn-primary btn-block ml-auto" href="{{ url('/users/create') }}">
                <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                Create User
                </a>                
            </div>
        </div>


    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Job Title</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>               
            <tbody>      
                @foreach ($data as $key => $user)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                        <span class="badge {{ $user->is_admin ? 'bg-success' : 'bg-info' }}">
    {{ $user->is_admin ? 'Admin' : 'Normal User' }}
</span>
                        </td>
                        <td>{{ $user->department}}</td>
                        <td>{{ $user->section}}</td>
                        <td>{{ $user->jobtitle}}</td>
                        <td>
                        <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                            <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>
                            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                            @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
{!! $data->render() !!}
</div>
</section>
</div>
</div>
@endsection
