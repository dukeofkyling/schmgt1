@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Performance Analytics</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Average Score</th>
                    <th>Total Subjects</th>
                    <th>Performance Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($analytics as $analytic)
                    <tr>
                        <td>{{ $analytic->student->name }}</td>
                        <td>{{ $analytic->average_score }}</td>
                        <td>{{ $analytic->total_subjects }}</td>
                        <td>{{ $analytic->performance_level }}</td>
                        <td>
                            <a href="{{ route('performance-analytics.show', $analytic->student_id) }}" class="btn btn-info">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
