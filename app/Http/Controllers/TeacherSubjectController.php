<?php
namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function showTeachers()
    {
        $teachers = Teacher::with('subject')->get();
        $subjects = Subject::all();
        return view('dos.teachers.index', compact('teachers', 'subjects'));
    }

    public function registerTeacher(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:teachers,email',
        'phone_number' => 'required|string|max:20',
        'employment_date' => 'required|date',
        'subject_name' => 'required|string|max:255',
    ]);
    
    // First find or create the subject
    $subject = Subject::firstOrCreate(['name' => $request->subject_name]);
    
    // Create teacher with subject_id
    $teacher = Teacher::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'employment_date' => $request->employment_date,
        'subject_id' => $subject->id
    ]);
    
    return redirect()->route('dos.teachers.index')
        ->with('success', 'Teacher registered successfully with subject: ' . $subject->name);
}

    public function manageSubjects()
    {
        $subjects = Subject::all();
        return view('dos.subjects.index', compact('subjects'));
    }

    public function createSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
        ]);

        Subject::create($request->only('name'));

        return redirect()->route('dos.subjects.index')->with('success', 'Subject added successfully.');
    }
    public function destroy(Teacher $teacher)
{
    $teacher->delete();

    return redirect()->route('dos.teachers.index')->with('success', 'Teacher deleted successfully.');
}

}
