@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Manage Teachers & Subjects</h1>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('dos.teachers.register') }}" method="POST" class="mb-6">
        @csrf
        <div class="mb-4">
            <label for="name" class="block">Teacher Name</label>
            <input type="text" name="name" class="w-full border rounded p-2">
        </div>
        
        <div class="mb-4">
            <label for="email" class="block">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2">
        </div>
        
        <div class="mb-4">
            <label for="phone_number" class="block">Phone Number</label>
            <input type="text" name="phone_number" class="w-full border rounded p-2">
        </div>
        
        <div class="mb-4">
            <label for="employment_date" class="block">Employment Date</label>
            <input type="date" name="employment_date" class="w-full border rounded p-2">
        </div>
        
        <div class="mb-4">
            <label for="subject_name" class="block">Subject</label>
            <input type="text" name="subject_name" class="w-full border rounded p-2" placeholder="Enter new subject name">
        </div>
        
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Register Teacher</button>
    </form>
    
    <h2 class="text-lg font-semibold mb-2">Existing Teachers</h2>
    @foreach ($teachers as $teacher)
        <div class="mb-4 p-4 bg-gray-100 rounded">
            <h3 class="font-semibold">{{ $teacher->name }} ({{ $teacher->email }})</h3>
            <p>Subject:
                @if ($teacher->subject)
                    <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded">{{ $teacher->subject->name }}</span>
                @else
                    <span class="text-gray-500">No subject assigned</span>
                @endif
            </p>
            <form action="{{ route('dos.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete</button>
</form>

        </div>
    @endforeach
</div>
@endsection