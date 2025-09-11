@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>Performance Data for {{ $user->name }}</h1>

    {{-- Purposes --}}
    <h3>Purposes</h3>
    <a href="{{ route('purposes.create') }}" class="btn btn-primary mb-2">Add Purpose</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Period</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purposes as $purpose)
                <tr>
                    <td>{{ $purpose->purpose }}</td>
                    <td>{{ $purpose->period->year ?? '-' }}</td>
                    <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('purposes.show', $purpose->id) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('purposes.edit', $purpose->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('purposes.destroy', $purpose->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Delete this purpose?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No purposes found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Objectives --}}
    <h3>Objectives</h3>
    <a href="{{ route('objectives.create') }}" class="btn btn-primary mb-2">Add Objective</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Objective</th>
                <th>Target</th>
                <th>Period</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($objectives as $objective)
                <tr>
                    <td>{{ $objective->objective }}</td>
                    <td>{{ $objective->target->target_name ?? '-' }}</td>
                    <td>{{ $objective->period->year ?? '-' }}</td>
                    <td>
                        <a href="{{ route('objectives.show', $objective->id) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('objectives.edit', $objective->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Delete this objective?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No objectives found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Initiatives --}}
    <h3>Initiatives</h3>
    <a href="{{ route('initiatives.create') }}" class="btn btn-primary mb-2">Add Initiative</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Initiative</th>
                <th>Objective</th>
                <th>Target</th>
                <th>Period</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($initiatives as $initiative)
                <tr>
                    <td>{{ $initiative->initiative }}</td>
                    <td>{{ $initiative->objective->objective ?? '-' }}</td>
                    <td>{{ $initiative->target->target_name ?? '-' }}</td>
                    <td>{{ $initiative->period->year ?? '-' }}</td>
                    <td>
                        <a href="{{ route('initiatives.show', $initiative->id) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('initiatives.edit', $initiative->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('initiatives.destroy', $initiative->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Delete this initiative?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No initiatives found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Generate Report --}}
    <h3>Generate Report</h3>
    <form method="POST" action="{{ route('report.generate') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="form-group">
            <label for="period_id">Select Period:</label>
            <select name="period_id" class="form-control" required>
                @foreach(\App\Models\Period::all() as $period)
                    <option value="{{ $period->id }}">{{ $period->year }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
    {{-- Approval Actions --}}
<hr>
<h3>Approval Actions</h3>

{{-- Approve Button --}}
<form method="POST" action="{{ route('manager.users.approve', [$user->id, $purposes->first()->period_id ?? 1]) }}" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-success">Approve</button>
</form>

{{-- Reject Button (Triggers Modal) --}}
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
    Reject
</button>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('manager.users.reject', [$user->id, $purposes->first()->period_id ?? 1]) }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to reject this user’s performance data?</p>
          <div class="form-group">
              <label for="comment">Rejection Comment</label>
              <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm Reject</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</section>
</div>
</div>
@endsection
