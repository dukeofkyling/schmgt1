@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-6">Director of Studies Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

        {{-- Teacher & Subject Management --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Teacher & Subject Oversight</h2>
            <p class="text-sm text-gray-600 mb-4">Register teachers, manage subject catalog.</p>
            <a href="{{ route('dos.teachers.index') }}" class="text-indigo-600 hover:underline">Manage Teachers & Subjects →</a>
        </div>

        {{-- Result Compilation --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Result Compilation</h2>
            <p class="text-sm text-gray-600 mb-4">Compile and submit student results.</p>
            <a href="{{ route('dos.results.index') }}" class="text-indigo-600 hover:underline">Compile Results →</a>
      
      
        </div>


        {{-- Performance Analytics --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Performance Analytics</h2>
            <p class="text-sm text-gray-600 mb-4">View academic performance reports.</p>
            <a href="{{ route('dos.analytics') }}" class="text-indigo-600 hover:underline">View Analytics →</a>
        </div>

        {{-- Timetable Management --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Timetable Management</h2>
            <p class="text-sm text-gray-600 mb-4">Upload or create lesson timetables.</p>
            <a href="{{ route('dos.timetable') }}" class="text-indigo-600 hover:underline">Manage Timetables →</a>
        </div>

        {{-- Reports & Resources --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Reports & Resources</h2>
            <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                <li><a href="{{ route('dos.circulars') }}" class="text-indigo-600 hover:underline">View Circulars</a></li>
                <li><a href="{{ route('dos.result_reports') }}" class="text-indigo-600 hover:underline">Result Reports</a></li>
                <li><a href="{{ route('dos.student_profiles') }}" class="text-indigo-600 hover:underline">Student Profiles</a></li>
               
                
                
            </ul>
        </div>

    </div>
</div>
@endsection
