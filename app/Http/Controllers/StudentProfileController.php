<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\StudentResult;
use App\Models\Timetable;

class StudentProfileController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('dos.students.index', compact('students'));
    }
    
    public function show($studentId)
    {
        $student = Student::with(['class', 'results' => function($query) {
            $query->where('term_id', currentTerm()->id);
        }])->findOrFail($studentId);
        
        return view('dos.students.show', compact('student'));
    }
}