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
                            <h1>Edit Purpose</h1>
                        </div>
                        <div class="pull-right">
                     </div>
                    </div>
                </div>

                <div class="card card-default">
                    <div class="card-body">
                    <form action="{{ route('purposes.update', $purpose) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- This indicates that this is a PUT request -->

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
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="purpose">Purpose:</label>
                                        <input type="text" name="purpose" value="{{ $purpose->purpose }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="period_id">Select Period:</label>
                                        <select name="period_id" class="form-control select2" required>
                                            @foreach ($periods as $period)
                                                <option value="{{ $period->id }}" {{ $period->id == $purpose->period_id ? 'selected' : '' }}>
                                                    {{ $period->year }}
                                                </option>
                                            @endforeach
                                        </select>
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