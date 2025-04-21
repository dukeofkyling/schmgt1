<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::with('subjects')->paginate(10);
        return view('dos.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('dos.teachers.create', compact('subjects'));
    }

    /**
     * Store a newly created teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'qualification' => 'required|string',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        DB::beginTransaction();

        try {
            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password'), // Default password
                'role' => 'teacher',
            ]);

            // Create teacher profile
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'qualification' => $validated['qualification'],
            ]);

            // Assign subjects
            $teacher->subjects()->attach($validated['subjects']);

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'Created Teacher',
                'description' => "Added new teacher: {$teacher->name}",
            ]);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher registered successfully. Default password has been set.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error registering teacher: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified teacher.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('subjects');
        return view('dos.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        $subjects = Subject::all();
        $assignedSubjects = $teacher->subjects->pluck('id')->toArray();
        
        return view('dos.teachers.edit', compact('teacher', 'subjects', 'assignedSubjects'));
    }

    /**
     * Update the specified teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'qualification' => 'required|string',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        DB::beginTransaction();

        try {
            // Update user
            $user = User::find($teacher->user_id);
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update teacher
            $teacher->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'qualification' => $validated['qualification'],
            ]);

            // Update subjects
            $teacher->subjects()->sync($validated['subjects']);

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'Updated Teacher',
                'description' => "Updated teacher: {$teacher->name}",
            ]);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating teacher: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified teacher from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();

        try {
            $name = $teacher->name;
            
            // Detach subjects
            $teacher->subjects()->detach();
            
            // Delete teacher
            $teacher->delete();
            
            // Delete user account (optional)
            User::find($teacher->user_id)->delete();

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'Deleted Teacher',
                'description' => "Removed teacher: {$name}",
            ]);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher removed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error removing teacher: ' . $e->getMessage());
        }
    }

    /**
     * Assign subjects to a teacher.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function assignSubjects(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        try {
            $teacher->subjects()->sync($validated['subjects']);

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'Assigned Subjects',
                'description' => "Updated subjects for teacher: {$teacher->name}",
            ]);

            return redirect()->route('teachers.show', $teacher)
                ->with('success', 'Subjects assigned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error assigning subjects: ' . $e->getMessage());
        }
    }
}