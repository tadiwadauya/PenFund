@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Strengths & Learning Areas</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ================= Self-Perception ================= --}}
    <h3>My Perception</h3>

    {{-- Self Strengths and Learning Areas Side by Side --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="POST" action="{{ route('strengths.store') }}">
                @csrf
                <div class="input-group mb-2">
                    <textarea name="strength" class="form-control" placeholder="Add a strength"></textarea>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('strengths.learning.storeLearningArea') }}">
                @csrf
                <div class="input-group mb-2">
                    <textarea name="learning_area" class="form-control" placeholder="Add a learning area"></textarea>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Self-Perception Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Strength</th>
                <th>Learning Area</th>
            </tr>
        </thead>
        <tbody>
            @php
                $max = max($selfStrengths->count(), $selfLearning->count());
            @endphp
            @for($i = 0; $i < $max; $i++)
                @php
                    $strength = $selfStrengths[$i] ?? null;
                    $learning = $selfLearning[$i] ?? null;
                @endphp
                <tr>
                    {{-- Strength --}}
                    <td>
                        @if($strength)
                        <form method="POST" action="{{ route('strengths.update', $strength->id) }}" class="d-flex">
                            @csrf
                            @method('PATCH')
                            <textarea name="strength" class="form-control me-2">{{ $strength->strength }}</textarea>
                            <button type="submit" class="btn btn-success me-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('strengths.destroy', $strength->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this strength?')">Delete</button>
                        </form>
                        @endif
                    </td>

                    {{-- Learning --}}
                    <td>
                        @if($learning)
                        <form method="POST" action="{{ route('learning.update', $learning->id) }}" class="d-flex">
                            @csrf
                            @method('PATCH')
                            <textarea name="learning_area" class="form-control me-2">{{ $learning->learning_area }}</textarea>
                            <button type="submit" class="btn btn-success me-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('learning.destroy', $learning->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this learning area?')">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

    <hr>

    {{-- ================= Assessor Perception ================= --}}
    <h3>Assessor Perception</h3>

    {{-- Assessor Strengths / Learning Side by Side --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="POST" action="{{ route('strengths.assessor.store') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <div class="input-group mb-2">
                    <textarea name="strength" class="form-control" placeholder="Add a strength"></textarea>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('strengths.learning.assessor.store') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <div class="input-group mb-2">
                    <textarea name="learning_area" class="form-control" placeholder="Add a learning area"></textarea>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Assessor Perception Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Strength</th>
                <th>Learning Area</th>
            </tr>
        </thead>
        <tbody>
            @php
                $max = max($assessorStrengths->count(), $assessorLearning->count());
            @endphp
            @for($i = 0; $i < $max; $i++)
                @php
                    $strength = $assessorStrengths[$i] ?? null;
                    $learning = $assessorLearning[$i] ?? null;
                @endphp
                <tr>
                    {{-- Strength --}}
                    <td>
                        @if($strength)
                        <form method="POST" action="{{ route('strengths.assessor.update', $strength->id) }}" class="d-flex">
                            @csrf
                            @method('PATCH')
                            <textarea name="strength" class="form-control me-2">{{ $strength->strength }}</textarea>
                            <button type="submit" class="btn btn-success me-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('strengths.assessor.destroy', $strength->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this strength?')">Delete</button>
                        </form>
                        @endif
                    </td>

                    {{-- Learning --}}
                    <td>
                        @if($learning)
                        <form method="POST" action="{{ route('learning.assessor.update', $learning->id) }}" class="d-flex">
                            @csrf
                            @method('PATCH')
                            <textarea name="learning_area" class="form-control me-2">{{ $learning->learning_area }}</textarea>
                            <button type="submit" class="btn btn-success me-1">Save</button>
                        </form>
                        <form method="POST" action="{{ route('learning.assessor.destroy', $learning->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this learning area?')">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

</div>
@endsection
