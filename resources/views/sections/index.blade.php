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
                <h2>Sections</h2>
            </div>
            <div class="pull-right">
            
                <a class="btn btn-success" href="{{ route('sections.create') }}"> Create New Section</a>
              
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Section</th>
            <th>Deparment</th>
            <th>Manager</th>
            <th>Assistant Manager</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($sections as $section)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $section->section }}</td>
            <td>
            @if(isset($departments[$section->department]))
                {{ $departments[$section->department]->department }} 
            @else
                Not Assigned
            @endif
        </td>
            <td>
            @if(isset($users[$section->manager]))
                {{ $users[$section->manager]->first_name }} - {{ $users[$section->manager]->last_name }}
            @else
                Not Assigned
            @endif
        </td>
         <td> 
            @if(isset($users[$section->asst_manager]))
                {{ $users[$section->asst_manager]->first_name }} - {{ $users[$section->asst_manager]->last_name }}
            @else
                Not Assigned
            @endif
        </td></td>
            <td>
                <form action="{{ route('sections.destroy',$section->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('sections.show',$section->id) }}">Show</a>
                    
                    <a class="btn btn-primary" href="{{ route('sections.edit',$section->id) }}">Edit</a>
               
                    @csrf
                    @method('DELETE')
                
                    <button type="submit" class="btn btn-danger">Delete</button>
                   
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $sections->links() !!}

</div>
</section>
</div>
</div>
@endsection

