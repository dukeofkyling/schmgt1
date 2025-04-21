<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

@extends('layouts.app') <!-- If you're using a layout, otherwise remove this -->

@section('content')
<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Upload / Update Timetable</h2>
        <a href="{{ route('dos.dashboard') }}" class="btn btn-dark">
            ⬅️ Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('dos.timetable.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <select name="class_id" class="form-select" required>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="timetable" class="form-label">Upload Timetable (PDF)</label>
                    <input type="file" name="timetable" class="form-control" accept="application/pdf" required>
                </div>

                <button type="submit" class="btn btn-primary">Upload / Update</button>
            </form>
        </div>
    </div>

    <h3 class="mb-3">Existing Timetables</h3>
    <div class="row">
        @foreach ($timetables as $classId => $timetable)
            <div class="col-md-6 mb-3">
                <div class="card border-info h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $timetable->class->name ?? 'N/A' }}</h5>
                        <a href="{{ asset('storage/' . $timetable->timetable_path) }}" target="_blank" class="btn btn-outline-info">
                            📄 View Timetable
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
