@extends('layouts.app')
@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">
<h3>{{ $user->department }}</h3>
    <h3> {{ $user->jobtitle }} PERFORMANCE TARGETS FOR THE PERIOD JAN – DEC {{ $period->year }}</h3>

    {{-- ================== USER DETAILS ================== --}}
    <table class="table table-bordered mb-4">
    <tr>
        <!-- Employee Details (Left) -->
        <td width="50%">
            <p><strong>Name of Staff Member Being Assessed:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Department:</strong> {{ $user->department }}</p>
            <p><strong>Section:</strong> {{ $user->section }}</p>
            <p><strong>Job Title:</strong> {{ $user->jobtitle }}</p>
            <p><strong>Grade:</strong> {{ $user->grade }}</p>
        </td>

        <!-- Supervisor Details (Right) -->
        <td width="50%">
            <p><strong>Assessor:</strong> {{ $user->supervisor ? $user->supervisor->first_name . ' ' . $user->supervisor->last_name : 'N/A' }}</p>
            <p><strong>Reviewer:</strong> {{ $user->reviewer ? $user->reviewer->first_name . ' ' . $user->reviewer->last_name : 'N/A' }}</p>
            <p><strong>Review Period:</strong> From 01 January {{ $period->year }} to  December {{ $period->year }}</p>
            <!-- <p><strong>Superior Department:</strong> {{ $user->supervisor ? $user->supervisor->department : 'N/A' }}</p>
            <p><strong>Superior Section:</strong> {{ $user->supervisor ? $user->supervisor->section : 'N/A' }}</p> -->
        </td>
    </tr>
</table>

{{-- KEY TASK --}}
    <a href="{{ route('targets.create') }}" class="btn btn-primary ml-auto">Add New Key Task</a>
    <h3>KEY TASK</h3>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Key Task</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($targets as $target)
            <tr>
                <td>{{ $target->target_name }}</td>
                <td>
                    
                   
                    <form action="{{ route('targets.destroy', $target->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this task?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No targets found.</td>
            </tr>
        @endforelse
    </tbody>
</table>


    
    <br>
<hr>
<br>


    {{-- Objectives --}}
    <h3>OBJECTIVES</h3>
    <a href="{{ route('objectives.create') }}" class="btn btn-primary mb-2">Add Objective</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Key Task</th>
                <th>Objective</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($objectives as $objective)
                <tr>
                    <td>{{ $objective->target->target_name ?? '-' }}</td>
                    <td>{{ $objective->objective }}</td>
                    <td>
                       
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
    <h3>Task and Targets</h3>
    <a href="{{ route('initiatives.create') }}" class="btn btn-primary mb-2">Add Action</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Key Task</th>
                <th>Objective</th>
                <th>Task</th>
                <th>Target</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($initiatives as $initiative)
                <tr>
                    <td>{{ $initiative->target->target_name ?? '-' }}</td>
                    <td>{{ $initiative->objective->objective ?? '-' }}</td>
                    <td>{{ $initiative->initiative }}</td>
                    <td>{{ $initiative->budget }}</td>
                    <td>
                        <form action="{{ route('initiatives.destroy', $initiative->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Delete this action?')">Delete</button>
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
