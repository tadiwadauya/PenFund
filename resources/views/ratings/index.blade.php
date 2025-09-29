@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Ratings</h2>

    <form action="{{ route('ratings.saveAll') }}" method="POST">
        @csrf

        @foreach($sections as $section)
            <h4 class="mt-4">Section: {{ $section->name }}</h4>

            <table class="table table-bordered mb-4">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Self Rating</th>
                        <th>Self Comment</th>
                        <th>Assessor Rating</th>
                        <th>Assessor Comment</th>
                        <th>Reviewer Rating</th>
                        <th>Reviewer Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($section->tasks as $task)
                        @php
                            $rating = $task->ratings->first();
                        @endphp
                        <tr>
                            <td><strong>{{ $task->name }}</strong></td>

                            <!-- Self Rating Dropdown -->
                            <td>
                                <select name="ratings[{{ $task->id }}][self_rating]" class="form-control">
                                    <option value="">-- Select --</option>
                                    @foreach(range(6,1) as $value)
                                        <option value="{{ $value }}"
                                            {{ isset($rating->self_rating) && $rating->self_rating == $value ? 'selected' : '' }}>
                                            {{ $gradeFromNumber($value) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <!-- Editable Self Comment -->
                            <td>
                                <input type="text" name="ratings[{{ $task->id }}][self_comment]" 
                                       value="{{ $rating->self_comment ?? '' }}" class="form-control">
                            </td>

                            <!-- Read-only Assessor & Reviewer Ratings/Comments -->
                            <td>{{ isset($rating->assessor_rating) ? $gradeFromNumber($rating->assessor_rating) : '-' }}</td>
                            <td>{{ $rating->assessor_comment ?? '-' }}</td>
                            <td>{{ isset($rating->reviewer_rating) ? $gradeFromNumber($rating->reviewer_rating) : '-' }}</td>
                            <td>{{ $rating->reviewer_comment ?? '-' }}</td>
                        </tr>
                    @endforeach

                    <!-- Overall Rating Row for Section -->
                    @php
                        $overallSection = collect($section->tasks)->map(function($task) {
                            $rating = $task->ratings->first();
                            return $rating->self_rating ?? null;
                        })->filter()->avg();
                    @endphp
                    <tr style="background-color: #f2f2f2; font-weight: bold;">
                        <td>Overall Rating for Section</td>
                        <td>{{ $overallSection ? $gradeFromNumber($overallSection) : '-' }}</td>
                        <td colspan="5"></td>
                    </tr>

                </tbody>
            </table>
        @endforeach

        <button type="submit" class="btn btn-primary">Save All Ratings</button>
    </form>
</div>
@endsection
