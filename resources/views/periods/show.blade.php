@extends('layouts.app')

@section('content')
    <h1>Period Details</h1>
    <p>Year: {{ $period->year }}</p>
    <a href="{{ route('periods.index') }}">Back to Periods</a>
@endsection