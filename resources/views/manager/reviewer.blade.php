@extends('layouts.app')

@section('content')
<div class="wrapper">
@include('includes.nav')
@include('includes.sidebar')

<div class="content-wrapper">
<section class="content">
<div class="container-fluid">

    <h1>Performance Appraisal for {{ $user->first_name }} {{ $user->last_name }}</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    {{-- ================== PURPOSES ================== --}}
    <h3>Purposes</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Period</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purposes as $purpose)
                <tr>
                    <td>{!! $purpose->purpose !!}</td>
                    <td>{{ $purpose->period->year ?? 'N/A' }}</td>
                    <td>{{ $purpose->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No purposes found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================== INITIATIVES WITH INLINE EDIT ================== --}}
    <h3>Performance Appraisal</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Period</th>
                <th>Target</th>
                <th>Objective</th>
                <th>Initiative</th>
                <th>Budget</th>
                <th>Rating</th>
                <th>Overall Ratings of Assessor </th> 
                <th>Staff member Comment</th>
                <th>Actual/Achieved </th>
                <th>Overall Ratings of Reviewer </th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            @forelse($initiatives as $initiative)
                <tr>
                    <td>{{ $initiative->period->year ?? 'N/A' }}</td>
                    <td>{{ $initiative->target->target_name ?? '-' }}</td>
                    <td>{{ $initiative->objective->objective ?? '-' }}</td>
                    <td>{{ $initiative->initiative }}</td>
                    <td>{{ $initiative->budget }}</td>
                    <td>
    @php
        $ratingLabel = match($initiative->rating) {
            6 => 'A1 - Outstanding performance. High levels of expertise',
            5 => 'A2 - Consistently exceeds requirements',
            4 => 'B1 - Meets requirements. Occasionally exceeds them',
            3 => 'B2 - Meets requirements',
            2 => 'C1 - Partially meets requirements. Improvement required',
            1 => 'C2 - Unacceptable. Well below standard required',
            default => '-',
        };
    @endphp
    

    {{ $ratingLabel }}
</td><td>
    @php
        $ratingLabel2 = match($initiative->supervisorrating) {
            6 => 'A1 - Outstanding performance. High levels of expertise',
            5 => 'A2 - Consistently exceeds requirements',
            4 => 'B1 - Meets requirements. Occasionally exceeds them',
            3 => 'B2 - Meets requirements',
            2 => 'C1 - Partially meets requirements. Improvement required',
            1 => 'C2 - Unacceptable. Well below standard required',
            default => '-',
        };
    @endphp
    

    {{ $ratingLabel2 }}
</td>
<td>
                           {{ $initiative->comment }}
                        </td>
                    
                    {{-- Inline edit form --}}
                    <form action="{{ route('initiatives.updateInline', $initiative->id) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        @method('PATCH')
                        <td>
                            <select name="archieved" class="form-control me-2" style="width: 120px;">
                                <option value="0" {{ $initiative->archieved == 0 ? 'selected' : '' }}>Not Achieved</option>
                                <option value="1" {{ $initiative->archieved == 1 ? 'selected' : '' }}>Achieved</option>
                            </select>
                        </td>
                        <td>
                            <select name="reviewerrating" class="form-control me-2" style="width: 180px;">
                                <option value="">-- Select Rating --</option>
                                <option value="6" {{ $initiative->reviewerrating == 6 ? 'selected' : '' }}>A1</option>
                                <option value="5" {{ $initiative->reviewerrating == 5 ? 'selected' : '' }}>A2</option>
                                <option value="4" {{ $initiative->reviewerrating == 4 ? 'selected' : '' }}>B1</option>
                                <option value="3" {{ $initiative->reviewerrating == 3 ? 'selected' : '' }}>B2</option>
                                <option value="2" {{ $initiative->reviewerrating == 2 ? 'selected' : '' }}>C1</option>
                                <option value="1" {{ $initiative->reviewerrating == 1 ? 'selected' : '' }}>C2</option>
                            </select>
                        </td>
                        
                        <td>
                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                        </td>
                    </form>
                  
                </tr>
            @empty
                <tr><td colspan="8">No initiatives found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================== ALL RATINGS IN ONE TABLE ================== --}}
@php
    $ratings = $initiatives
        ->groupBy('target_id')
        ->map(function($group) {
            return [
                'target_name' => $group->first()->target->target_name ?? 'N/A',
                'employee_avg' => $group->avg('rating'),
                'supervisor_avg' => $group->avg('supervisorrating'),
                'reviewer_avg' => $group->avg('reviewerrating'),
            ];
        });

    $overallEmployee = $initiatives->avg('rating');
    $overallSupervisor = $initiatives->avg('supervisorrating');
    $overallReviewer = $initiatives->avg('reviewerrating');

    if (!function_exists('mapRating')) {
        function mapRating($value) {
            return match(true) {
                $value >= 5.5 => 'A1',
                $value >= 4.5 => 'A2',
                $value >= 3.5 => 'B1',
                $value >= 2.5 => 'B2',
                $value >= 1.5 => 'C1',
                $value > 0    => 'C2',
                default       => 'No Rating',
            };
        }
    }
@endphp

@if($ratings->count())
    <h4 class="mt-4">Performance Ratings Summary</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Target</th>
                <th>Staff Member Rating</th>
                <th>Assessor Rating</th>
                <th>Reviewer Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ratings as $r)
                <tr>
                    <td>{{ $r['target_name'] }}</td>
                    <td>{{ mapRating($r['employee_avg']) }}</td>
                    <td>{{ mapRating($r['supervisor_avg']) }}</td>
                    <td>{{ mapRating($r['reviewer_avg']) }}</td>
                </tr>
            @endforeach
            {{-- Totals Row --}}
            <tr class="font-weight-bold">
                <td>Total Performance Notches</td>
                <td>{{ mapRating($overallEmployee) }}</td>
                <td>{{ mapRating($overallSupervisor) }}</td>
                <td>{{ mapRating($overallReviewer) }}</td>
            </tr>
        </tbody>
    </table>
@endif

    {{-- ================== AUTHORISATION ================== --}}
    <hr>
    <h3>Authorisations by Period</h3>

    @php
        $periods = $initiatives->pluck('period')->unique('id')->filter();
    @endphp

    @foreach($periods as $p)
        @php
            $auth = $authorisations->where('period_id', $p->id)->first();
        @endphp

        <div class="mb-3 p-2 border rounded">
            <strong>Period: {{ $p->year }}</strong><br>

            @if(!$auth)
                <form method="POST" action="{{ route('user.performanceapraisal.submit', ['user' => $user->id, 'period' => $p->id]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Submit for authorisation</button>
                </form>
            @elseif($auth->status === 'Rejected')
                <form method="POST" action="{{ route('user.performanceapraisal.submit', ['user' => $user->id, 'period' => $p->id]) }}">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Resubmit for authorisation</button>
                </form>
            @else
                <span class="badge badge-success">{{ $auth->status }}</span>
            @endif
        </div>
    @endforeach

    {{-- ================== REPORT GENERATION ================== --}}
    <hr>
    <h3>Generate Report</h3>
    <form method="POST" action="{{ route('appraisalreport.apgenerate') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="form-group">
            <label for="period_id">Select Period:</label>
            <select name="period_id" class="form-control" required>
                @foreach(\App\Models\Period::all() as $p)
                    <option value="{{ $p->id }}">{{ $p->year }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-info">Generate Report</button>
    </form>
    {{-- Approval Actions --}}
<hr>
<h3>Approval Actions</h3>

@php
    // Get the latest authorisation for this period
    $latestAuth = $authorisations->where('period_id', $purposes->first()->period_id ?? 1)
                                  ->sortByDesc('created_at')
                                  ->first();
@endphp

@if($latestAuth && $latestAuth->status === 'Authorized')
    {{-- Approve Button --}}
    <form method="POST" action="{{ route('manager.reviewers.approve', [$user->id, $purposes->first()->period_id ?? 1]) }}" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-success">Authorize</button>
    </form>

    {{-- Reject Button (Triggers Modal) --}}
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
        Reject
    </button>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form method="POST" action="{{ route('manager.reviewers.reject', [$user->id, $purposes->first()->period_id ?? 1]) }}">
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
@endif

</div>
</section>
</div>
</div>
@endsection
