@extends('layouts.app')
@section('content')

<div class="content-header">
    <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    Vakai system Developed by <a href="{{ url('http://basilamark.co.zw') }}" class="m-0">Indelible Mark IT solutions</a>
                    <h1 class="m-0">Users Management</h1>
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
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1> {{ $user->name }}'s Information</h1>
            </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <a href="{{ route('users.index') }}" class="btn btn-success > <li breadcrumb-item active">Back to users</li></a>
            </ol>
          </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                            <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if ($user->profile && $user->profile->avatar_status == 1)
                                <img class="profile-user-img img-fluid img-circle" src="{{ $user->profile->avatar }}" alt="{{ $user->name }}">
                            @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('/design/dist/img/avatar.png') }}" alt="{{ $user->name }}" class="user-avatar">
                            @endif
                        </div>
                            <h3 class="profile-username text-center">  {{ $user->name }}</h3>
                            <p class="text-muted text-center">{{ $user->first_name }} {{ $user->last_name }}<br> {{ $user->job_title }}
                            <br>  
                            @if($user->email)
                            <span class="text-center" data-toggle="tooltip" data-placement="top" title="{{ trans('usersmanagement.tooltips.email-user', ['user' => $user->email]) }}">
                                {{ Html::mailto($user->email, $user->email) }}
                            </span>
                            @endif
                            </p>
                                <ul class="list-group list-group-unbordered mb-3">
                                    @if ($user->paynumber)
                                    <li class="list-group-item">
                                        <b>Paynumber:</b> <a class="float-right">  {{ $user->paynumber }}</a>
                                    </li>
                                     @endif
                                    @if ($user->department)
                                    <li class="list-group-item">
                                        <b>Departmenet</b> <a class="float-right">{{ $user->department }}</a>
                                    </li>
                                    @endif
                                 
                                    <li class="list-group-item">
                                        <b>Roles</b> 
                                        <a class="float-right"> @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                        <span class="badge rounded-pill bg-dark">{{ $v }}</span>
                                    @endforeach
                                    @endif
                                        </a>
                                    </li>
                                 



            </div>
        </div>
    </div>
</section>






<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {{ $user->email }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Roles:</strong>
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <span class="badge rounded-pill bg-dark">{{ $v }}</span>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection