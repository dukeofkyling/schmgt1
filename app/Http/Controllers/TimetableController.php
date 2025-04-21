<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::latest()->paginate(10);
        return view('dos.timetables.index', compact('timetables'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $classrooms = ClassRoom::all();
        return view('dos.timetables.create', compact('subjects', 'teachers', 'classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,xlsx,xls,doc,docx|max:5120',
            'description' => 'nullable|string',
            'publish_date' => 'required|date',
        ]);

        $path = $request->file('file')->store('timetables', 'public');

        $timetable = Timetable::create([
            'term' => $validated['term'],
            'academic_year' => $validated['academic_year'],
            'file_path' => $path,
            'description' => $validated['description'] ?? null,
            'publish_date' => $validated['publish_date'],
            'created_by' => auth()->id(),
        ]);

        // Logic to distribute timetable to stakeholders
        $this->distributeTimetable($timetable);

        return redirect()->route('dos.timetables.index')
            ->with('success', 'Timetable uploaded and distributed successfully.');
    }

    public function show(Timetable $timetable)
    {
        return view('dos.timetables.show', compact('timetable'));
    }

    public function edit(Timetable $timetable)
    {
        return view('dos.timetables.edit', compact('timetable'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $validated = $request->validate([
            'term' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'file' => 'nullable|mimes:pdf,xlsx,xls,doc,docx|max:5120',
            'description' => 'nullable|string',
            'publish_date' => 'required|date',
        ]);

        $data = [
            'term' => $validated['term'],
            'academic_year' => $validated['academic_year'],
            'description' => $validated['description'] ?? null,
            'publish_date' => $validated['publish_date'],
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($timetable->file_path)) {
                Storage::disk('public')->delete($timetable->file_path);
            }
            
            // Store new file
            $path = $request->file('file')->store('timetables', 'public');
            $data['file_path'] = $path;
            
            // Redistribute updated timetable
            $this->distributeTimetable($timetable);
        }

        $timetable->update($data);

        return redirect()->route('dos.timetables.index')
            ->with('success', 'Timetable updated successfully.');
    }

    public function destroy(Timetable $timetable)
    {
        // Delete file
        if (Storage::disk('public')->exists($timetable->file_path)) {
            Storage::disk('public')->delete($timetable->file_path);
        }
        
        $timetable->delete();

        return redirect()->route('dos.timetables.index')
            ->with('success', 'Timetable deleted successfully.');
    }

    private function distributeTimetable(Timetable $timetable)
    {
        // Logic to distribute timetable to teachers, head teacher, students, and parents
        // This might involve sending notifications, creating portal entries, etc.
        // For now, we'll just implement a placeholder
        
        // Example: Notify teachers
        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            // Notify teacher about new timetable
            // You can use Laravel notifications here
        }
        
        // Similarly for head teacher, students, and parents
    }
}