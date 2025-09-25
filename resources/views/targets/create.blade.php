@extends('layouts.app')
@section('content')
<div class="wrapper">
    @include('includes.nav')
    @include('includes.sidebar')

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <h1>Create Target</h1>
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn btn-primary" href="{{ route('targets.index') }}"> Back</a>
                    </div>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card card-default">
                    <div class="card-body">
                        <form action="{{ route('targets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="target_name">Target Name:</label>
                                        <input type="text" name="target_name" id="target_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="period_id">Period:</label>
                                        <select name="period_id" id="period_id" class="form-control" required>
                                            <option value="">-- Select Period --</option>
                                            @foreach($periods as $period)
                                                <option value="{{ $period->id }}">{{ $period->year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
