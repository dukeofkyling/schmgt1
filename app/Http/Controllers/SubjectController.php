<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Activity;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::withCount(['teachers', 'students'])->paginate(15);
        return view('dos.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dos.subjects.create');
    }

    /**
     * Store a newly created subject in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
            'code' => 'required|string|max:20|unique:subjects',
            'description' => 'nullable|string',
            'level' => 'required|string|max:50',
            'credits' => 'required|integer|min:0'
        ]);

        try {
            $subject = Subject::create($validated);

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'Created Subject',
                'description' => "Added new subject: {$subject->name} ({$subject->code})",
            ]);

            return redirect()->route('subjects.index')
                ->with('success', 'Subject added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding subject: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified subject.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subject->load(['teachers', 'students']);
        return view('dos.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified subject.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        return view('dos.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'level' => 'required|string|max:50',
            'credits' => 'required|integer|min:0'
        ]);

        try {
            $subject->update($validated);

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'Updated Subject',
                'description' => "Updated subject: {$subject->name} ({$subject->code})",
            ]);

            return redirect()->route('subjects.index')
                ->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating subject: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified subject from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        try {
            $name = $subject->name;
            $code = $subject->code;
            
            // Check if subject has related records
            if ($subject->teachers()->count() > 0 || $subject->students()->count() > 0) {
                return back()->with('error', 'Cannot delete subject that has associated teachers or students.');
            }
            
            $subject->delete();

            // Log activity
            Activity::create([
                'user_id' => auth()->id(),
                'action' => 'Deleted Subject',
                'description' => "Removed subject: {$name} ({$code})",
            ]);

            return redirect()->route('subjects.index')
                ->with('success', 'Subject removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing subject: ' . $e->getMessage());
        }
    }
}