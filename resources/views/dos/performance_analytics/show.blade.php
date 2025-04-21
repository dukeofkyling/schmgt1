@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $student->name }}'s Performance Analytics</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Term</th>
                    <th>Average Score</th>
                    <th>Total Subjects</th>
                    <th>Performance Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($analytics as $analytic)
                    <tr>
                        <td>{{ $analytic->term->name }}</td>
                        <td>{{ $analytic->average_score }}</td>
                        <td>{{ $analytic->total_subjects }}</td>
                        <td>{{ $analytic->performance_level }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
