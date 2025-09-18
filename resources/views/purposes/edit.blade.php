@extends('layouts.app')
@section('content')
<div class="wrapper">
    @include('includes.nav')
    @include('includes.sidebar')

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h1>Edit Purpose</h1>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary" href="{{ url()->previous() }}"> Back</a>
                        </div>
                    </div>
                </div>

                <div class="card card-default">
                    <div class="card-body">
                        <form action="{{ route('purposes.update', $purpose) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- PUT request -->

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-xs-9 col-sm-9 col-md-9">
                                    <div class="form-group">
                                        <label for="purpose">Purpose:</label>
                                        <div class="card-body">
                                            <textarea id="purpose" name="purpose" class="form-control" rows="5">{{ old('purpose', $purpose->purpose) }}</textarea>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
