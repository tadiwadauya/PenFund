@extends('layouts.app')

@section('content')
<div class="wrapper">
    @include('includes.nav')
    @include('includes.sidebar')

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">

                <h1>Add New Action to Support Objectives</h1>

                <form method="POST" action="{{ route('initiatives.store') }}">
                    @csrf

                  

                    {{-- Target --}}
                    <div class="form-group">
                        <label for="target_id">Select Target</label>
                        <select name="target_id" id="target_id" class="form-control" required>
                            <option value="">-- Choose Target --</option>
                            @foreach($targets as $target)
                                <option value="{{ $target->id }}">{{ $target->target_name }}</option>
                            @endforeach
                        </select>
                        @error('target_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    {{-- Objective --}}
                    <div class="form-group">
                        <label for="objective_id">Select Objective</label>
                        <select name="objective_id" id="objective_id" class="form-control" required>
                            <option value="">-- Choose Objective --</option>
                            @foreach($objectives as $objective)
                                <option value="{{ $objective->id }}">
                                    {{ $objective->objective }}
                                 
                                </option>
                            @endforeach
                        </select>
                        @error('objective_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    {{-- Initiative --}}
                    <div class="form-group">
                        <label for="initiative">Action To Support Objective</label>
                        <textarea name="initiative" id="initiative" class="form-control" rows="3" required>{{ old('initiative') }}</textarea>
                        @error('initiative') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Budget (optional) --}}
                    <div class="form-group">
                        <label for="budget">Budget (optional)</label>
                        <input type="text" name="budget" id="budget" class="form-control" value="{{ old('budget') }}">
                        @error('budget') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary">Save Action</button>
                    <a href="{{ route('user.performance.index') }}" class="btn btn-secondary">Cancel</a>
                </form>

            </div>
        </section>
    </div>
</div>
@endsection
