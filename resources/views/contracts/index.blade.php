@extends('layouts.app')

@section('content')
    <h1>Contracts</h1>
    <a href="{{ route('contracts.create') }}">Create Contract</a>
    <table>
        <thead>
            <tr>
                <th>Purpose</th>
                <th>First Name</th>
                <th>Job Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
                <tr>
                    <td>{{ $contract->purpose }}</td>
                    <td>{{ $contract->first_name }}</td>
                    <td>{{ $contract->job_title }}</td>
                    <td>
                        <a href="{{ route('contracts.edit', $contract->id) }}">Edit</a>
                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection