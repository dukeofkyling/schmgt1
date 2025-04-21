<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Result;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class ReportsController extends Controller
{
    public function circulars()
    {
        $circulars = Circular::latest()->paginate(10);
        return view('dos.reports.circulars', compact('circulars'));
    }

    public function showCircular(Circular $circular)
    {
        return view('dos.reports.show_circular', compact('circular'));
    }

    public function printCircular(Circular $circular)
    {
        $pdf = PDF::loadView('dos.reports.print_circular', compact('circular'));
        return $pdf->download('circular-' . $circular->id . '.pdf');
    }

    public function studentResults()
    {
        $students = Student::with('class')->get();
        return view('dos.reports.student_results', compact('students'));
    }

    public function showStudentResults(Student $student)
    {
        $results = Result::where('student_id', $student->id)
                        ->with(['subject', 'teacher'])
                        ->get()
                        ->groupBy('term');
        
        return view('dos.reports.show_student_results', compact('student', 'results'));
    }

    public function printStudentResults(Student $student, Request $request)
    {
        $term = $request->input('term');
        $academicYear = $request->input('academic_year');
        
        $results = Result::where('student_id', $student->id)
                        ->where('term', $term)
                        ->where('academic_year', $academicYear)
                        ->with(['subject', 'teacher'])
                        ->get();
        
        $pdf = PDF::loadView('dos.reports.print_student_results', compact('student', 'results', 'term', 'academicYear'));
        return $pdf->download('results-' . $student->id . '-' . $term . '-' . $academicYear . '.pdf');
    }

    public function studentProfiles()
    {
        $students = Student::with('class')->paginate(15);
        return view('dos.reports.student_profiles', compact('students'));
    }

    public function showStudentProfile(Student $student)
    {
        $student->load(['class', 'parent', 'results']);
        return view('dos.reports.show_student_profile', compact('student'));
    }

    public function printStudentProfile(Student $student)
    {
        $student->load(['class', 'parent', 'results']);
        $pdf = PDF::loadView('dos.reports.print_student_profile', compact('student'));
        return $pdf->download('profile-' . $student->id . '.pdf');
    }

    public function salary()
    {
        $user = auth()->user();
        $salary = $user->salary; // Assumes there's a salary relationship on the User model
        
        return view('dos.reports.salary', compact('salary'));
    }

    public function teacherProfiles()
    {
        $teachers = Teacher::paginate(15);
        return view('dos.reports.teacher_profiles', compact('teachers'));
    }

    public function showTeacherProfile(Teacher $teacher)
    {
        $teacher->load(['subjects', 'classes']);
        return view('dos.reports.show_teacher_profile', compact('teacher'));
    }

    public function printTeacherProfile(Teacher $teacher)
    {
        $teacher->load(['subjects', 'classes']);
        $pdf = PDF::loadView('dos.reports.print_teacher_profile', compact('teacher'));
        return $pdf->download('teacher-profile-' . $teacher->id . '.pdf');
    }

    public function subjectsList()
    {
        $subjects = Subject::with('teachers')->get();
        return view('dos.reports.subjects_list', compact('subjects'));
    }

    public function printSubjectsList()
    {
        $subjects = Subject::with('teachers')->get();
        $pdf = PDF::loadView('dos.reports.print_subjects_list', compact('subjects'));
        return $pdf->download('subjects-list.pdf');
    }
}