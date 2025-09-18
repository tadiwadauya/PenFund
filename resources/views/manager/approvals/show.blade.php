@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h1>Performance Target for {{ $user->first_name }} {{ $user->last_name }} — {{ $approval->period->year }}</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    {{-- Purposes --}}
    <h3>Purposes</h3>
    <table class="table table-bordered">
        <thead><tr><th>Purpose</th><th>Period</th><th>Created At</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($purposes as $purpose)
            <tr>
                <td>{!! $purpose->purpose !!}</td>
                <td>{{ $purpose->period->year ?? '-' }}</td>
                <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('purposes.show', $purpose->id) }}" class="btn btn-info btn-sm">Show</a>
                    <a href="{{ route('purposes.edit', $purpose->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('purposes.destroy', $purpose->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this purpose?')">Delete</button>
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
    <table class="table table-bordered">
        <thead><tr><th>Objective</th><th>Target</th><th>Period</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($objectives as $objective)
            <tr>
                <td>{{ $objective->objective }}</td>
                <td>{{ $objective->target->target_name ?? '-' }}</td>
                <td>{{ $objective->period->year ?? '-' }}</td>
                <td>
                    <a href="{{ route('objectives.show', $objective->id) }}" class="btn btn-info btn-sm">Show</a>
                    <a href="{{ route('objectives.edit', $objective->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this objective?')">Delete</button>
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
    <table class="table table-bordered">
        <thead><tr><th>Initiative</th><th>Objective</th><th>Target</th><th>Period</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($initiatives as $initiative)
            <tr>
                <td>{{ $initiative->initiative }}</td>
                <td>{{ $initiative->objective->objective ?? '-' }}</td>
                <td>{{ $initiative->target->target_name ?? '-' }}</td>
                <td>{{ $initiative->period->year ?? '-' }}</td>
                <td>
                    <a href="{{ route('initiatives.show', $initiative->id) }}" class="btn btn-info btn-sm">Show</a>
                    <a href="{{ route('initiatives.edit', $initiative->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('initiatives.destroy', $initiative->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this initiative?')">Delete</button>
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
        <input type="hidden" name="period_id" value="{{ $approval->period_id }}">
        <button type="submit" class="btn btn-primary mb-3">Generate Report (PDF)</button>
    </form>

    {{-- Approval actions --}}
    <div class="mt-4">
        <form method="POST" action="{{ route('manager.approvals.approve', $approval->id) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Approve this performance data?')">Approve</button>
        </form>

        <!-- Reject triggers modal -->
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">Reject</button>
    </div>

    <!-- Reject Modal (Bootstrap) -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('manager.approvals.reject', $approval->id) }}">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Performance Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <label for="comment">Rejection Comment <small class="text-muted">(required)</small></label>
                    <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Submit Rejection</button>
              </div>
            </div>
        </form>
      </div>
    </div>

</div>
@endsection
