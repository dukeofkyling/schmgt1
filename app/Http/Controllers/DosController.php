<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Result;
use App\Models\User;
use App\Models\Activity;
use App\Models\SalaryDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\Timetable;
use Illuminate\Support\Facades\Storage;
use App\Models\SchoolClass;

class DosController extends Controller
{
    /**
     * Display DOS dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
{
    return view('dos.dashboard');
}

    /**
     * Display DOS salary details.
     *
     * @return \Illuminate\Http\Response
     */
    public function salary()
    {
        $user = Auth::user();
        $salaryDetails = SalaryDetail::where('user_id', $user->id)->latest()->first();
        
        return view('dos.salary', compact('salaryDetails'));
    }
    public function profile() { return view('dos.profile'); }
    public function teacherSubject() { return view('dos.teacher_subject'); }
    public function results() { return view('dos.results'); }
    public function analytics() { return view('dos.analytics'); }
    public function timetable() { return view('dos.timetable'); }
    public function reports() { return view('dos.reports'); }
    
    public function showTimetables()
    {
        $classes = SchoolClass::all();
        $timetables = Timetable::with('class')->get();
    
        return view('dos.timetable.index', compact('classes', 'timetables'));
    
    }
    
    public function timetableUpload(Request $request)
{
    $request->validate([
        'class_id' => 'required|exists:classes,id',
        'timetable' => 'required|mimes:pdf|max:2048',
    ]);

    $existing = Timetable::where('class_id', $request->class_id)->first();

    $path = $request->file('timetable')->store('timetables', 'public');

    if ($existing) {
        // Optionally delete the old file
        Storage::disk('public')->delete($existing->timetable_path);

        // Update the existing record
        $existing->update([
            'timetable_path' => $path,
        ]);
    } else {
        // Create a new timetable
        Timetable::create([
            'class_id' => $request->class_id,
            'timetable_path' => $path,
        ]);
    }

    return redirect()->back()->with('success', 'Timetable uploaded/updated successfully!');
}

    public function store(Request $request)
{
    $request->validate([
        'class_id' => 'required|exists:classes,id',
        'timetable_file' => 'required|file|mimes:pdf',
    ]);

    $path = $request->file('timetable_file')->store('timetables', 'public');

    Timetable::create([
        'class_id' => $request->class_id,
        'file_path' => $path,
    ]);

    return redirect()->back()->with('success', 'Timetable uploaded successfully.');
}
public function showTimetableUploadForm()
{
    $classes = ClassModel::all(); // replace with your actual model
    $timetables = Timetable::with('class')->get();
    return view('dos.timetables.upload', compact('classes', 'timetables'));
}

public function timetableIndex()
{
    $classes = SchoolClass::all(); // adjust ClassModel to match your model
    $timetables = Timetable::all()->keyBy('class_id');

    return view('dos.timetable.index', compact('classes', 'timetables'));
}


}