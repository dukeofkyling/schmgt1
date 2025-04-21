<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\CompiledReport;
use App\Models\Term;
class ResultsCompilationController extends Controller
{
    //
    public function index()
    {
        // Logic to compile the results
        return view('dos.results.index'); // Make sure this view exists
    }
  

// In ResultsCompilationController.php

public function viewResults()
{
    $students = Student::with(['results.subject'])->get();
    $subjects = Subject::all(); // Load all subjects for the table header

    return view('dos.results.index', compact('students', 'subjects'));
}



public function compileResults(Request $request)
{
    // Get all students
    $students = Student::with('subjects', 'results')->get(); // assuming relations

    foreach ($students as $student) {
        $report = [];

        foreach ($student->subjects as $subject) {
            $result = $student->results->where('subject_id', $subject->id)->first();
            if ($result) {
                $report[] = [
                    'subject' => $subject->name,
                    'score' => $result->score,
                    // any other fields
                ];
            }
            $termId = \App\Models\Term::where('is_current', true)->value('id');

CompiledReport::create([
    'student_id' => $student->id,
    'term_id' => $termId,
    'report_data' => json_encode($report),
    'status' => 'forwarded_to_head',
]);

        }

        // Save or forward the compiled report
        // For example, save to a CompiledReport model
        CompiledReport::create([
            'student_id' => $student->id,
            'term_id' => \App\Models\Term::where('is_current', true)->value('id'),
            'report_data' => json_encode($report),
            'status' => 'forwarded_to_head',
        ]);
        
    }

    return redirect()->back()->with('success', 'Compiled and forwarded to Head Teacher.');
}


}
