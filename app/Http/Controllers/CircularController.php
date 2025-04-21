<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\StudentResult;
use App\Models\Timetable;
class CircularController extends Controller
{
    public function index()
    {
        $circulars = Circular::where('created_by', auth()->user()->directorOfStudy->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('dos.circulars.index', compact('circulars'));
    }
    
    public function create()
    {
        $terms = Term::all();
        return view('dos.circulars.create', compact('terms'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'term_id' => 'required|exists:terms,id',
            'publish_date' => 'required|date',
        ]);
        
        $circular = Circular::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'term_id' => $validated['term_id'],
            'created_by' => auth()->user()->directorOfStudy->id,
            'publish_date' => $validated['publish_date'],
        ]);
        
        return redirect()->route('dos.circulars.index')
                        ->with('success', 'Circular created successfully');
    }
    
    // Other CRUD methods for circulars
}