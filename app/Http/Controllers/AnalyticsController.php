<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Result;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AnalyticsController extends Controller
{
    /**
     * Display performance analytics dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $academicYears = Report::distinct()->pluck('academic_year');
        $terms = Report::distinct()->pluck('term');
        
        $selectedYear = $request->input('academic_year', $academicYears->first());
        $selectedTerm = $request->input('term', $terms->first());
        
        // Overall performance statistics
        $overallStats = Report::where('academic_year', $selectedYear)
                              ->where('term', $selectedTerm)
                              ->select(
                                  DB::raw('AVG(average_percentage) as avg_percentage'),
                                  DB::raw('COUNT(*) as total_students'),
                                  DB::raw('COUNT(CASE WHEN grade = "A" THEN 1 END) as a_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "B" THEN 1 END) as b_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "C" THEN 1 END) as c_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "D" THEN 1 END) as d_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "F" THEN 1 END) as f_count')
                              )
                              ->first();
        
        // Subject performance
        $subjectPerformance = Result::join('reports', 'results.report_id', '=', 'reports.id')
                                    ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                                    ->where('reports.academic_year', $selectedYear)
                                    ->where('reports.term', $selectedTerm)
                                    ->select(
                                        'subjects.name',
                                        DB::raw('AVG(results.total_marks) as avg_marks'),
                                        DB::raw('COUNT(*) as student_count')
                                    )
                                    ->groupBy('subjects.name')
                                    ->get();
        
        // Top performing students
        $topStudents = Report::with('student')
                            ->where('academic_year', $selectedYear)
                            ->where('term', $selectedTerm)
                            ->orderBy('average_percentage', 'desc')
                            ->take(10)
                            ->get();
        
        return view('dos.analytics.index', compact(
            'academicYears', 
            'terms', 
            'selectedYear', 
            'selectedTerm',
            'overallStats',
            'subjectPerformance',
            'topStudents'
        ));
    }
    
    /**
     * Generate detailed performance report for a specific student.
     *
     * @param int $studentId
     * @return \Illuminate\Http\Response
     */
    public function studentPerformance($studentId)
    {
        $student = Student::findOrFail($studentId);
        
        // Get student's reports across terms
        $reports = Report::where('student_id', $studentId)
                         ->orderBy('academic_year', 'desc')
                         ->orderBy('term', 'desc')
                         ->get();
        
        // Get subject performance over time
        $subjectPerformance = Result::where('student_id', $studentId)
                                    ->whereNotNull('report_id')
                                    ->join('reports', 'results.report_id', '=', 'reports.id')
                                    ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                                    ->select(
                                        'subjects.name',
                                        'reports.term',
                                        'reports.academic_year',
                                        'results.total_marks'
                                    )
                                    ->orderBy('reports.academic_year')
                                    ->orderBy('reports.term')
                                    ->get()
                                    ->groupBy('subjects.name');
        
        return view('dos.analytics.student', compact('student', 'reports', 'subjectPerformance'));
    }
    
    /**
     * Generate detailed performance report for a specific subject.
     *
     * @param int $subjectId
     * @return \Illuminate\Http\Response
     */
    public function subjectPerformance($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        
        // Get performance data across terms
        $performanceData = Result::where('subject_id', $subjectId)
                                ->whereNotNull('report_id')
                                ->join('reports', 'results.report_id', '=', 'reports.id')
                                ->select(
                                    'reports.term',
                                    'reports.academic_year',
                                    DB::raw('AVG(results.total_marks) as avg_marks'),
                                    DB::raw('COUNT(*) as student_count'),
                                    DB::raw('COUNT(CASE WHEN results.total_marks >= 80 THEN 1 END) as a_count'),
                                    DB::raw('COUNT(CASE WHEN results.total_marks >= 70 AND results.total_marks < 80 THEN 1 END) as b_count'),
                                    DB::raw('COUNT(CASE WHEN results.total_marks >= 60 AND results.total_marks < 70 THEN 1 END) as c_count'),
                                    DB::raw('COUNT(CASE WHEN results.total_marks >= 50 AND results.total_marks < 60 THEN 1 END) as d_count'),
                                    DB::raw('COUNT(CASE WHEN results.total_marks < 50 THEN 1 END) as f_count')
                                )
                                ->groupBy('reports.term', 'reports.academic_year')
                                ->orderBy('reports.academic_year')
                                ->orderBy('reports.term')
                                ->get();
        
        return view('dos.analytics.subject', compact('subject', 'performanceData'));
    }
    
    /**
     * Compare performance across different terms or years.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function comparePerformance(Request $request)
    {
        $academicYears = Report::distinct()->pluck('academic_year');
        $terms = Report::distinct()->pluck('term');
        
        $selectedYears = $request->input('years', [$academicYears->first()]);
        $selectedTerms = $request->input('terms', [$terms->first()]);
        
        // Prepare comparison data
        $comparisonData = [];
        
        foreach ($selectedYears as $year) {
            foreach ($selectedTerms as $term) {
                $data = Report::where('academic_year', $year)
                              ->where('term', $term)
                              ->select(
                                  DB::raw('AVG(average_percentage) as avg_percentage'),
                                  DB::raw('COUNT(*) as total_students'),
                                  DB::raw('COUNT(CASE WHEN grade = "A" THEN 1 END) as a_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "B" THEN 1 END) as b_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "C" THEN 1 END) as c_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "D" THEN 1 END) as d_count'),
                                  DB::raw('COUNT(CASE WHEN grade = "F" THEN 1 END) as f_count')
                              )
                              ->first();
                
                if ($data->total_students > 0) {
                    $comparisonData[] = [
                        'year' => $year,
                        'term' => $term,
                        'data' => $data
                    ];
                }
            }
        }
        
        return view('dos.analytics.compare', compact(
            'academicYears', 
            'terms', 
            'selectedYears', 
            'selectedTerms',
            'comparisonData'
        ));
    }
    
    /**
     * Export analytics data as CSV or PDF.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string',
            'term' => 'required|string',
            'format' => 'required|in:csv,pdf'
        ]);
        
        $year = $validated['year'];
        $term = $validated['term'];
        
        // Get data
        $reports = Report::with(['student', 'results.subject'])
                         ->where('academic_year', $year)
                         ->where('term', $term)
                         ->get();
        
        if ($reports->isEmpty()) {
            return back()->with('error', 'No data found for the selected criteria.');
        }
        
        // Log activity
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'Exported Analytics',
            'description' => "Exported performance analytics for {$term} {$year} in {$validated['format']} format"
        ]);
        
        if ($validated['format'] === 'csv') {
            // Generate CSV
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="performance_report_' . $year . '_' . $term . '.csv"',
            ];
            
            $callback = function() use ($reports) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'Student ID', 'Student Name', 'Average %', 'Grade', 
                    'Total Marks', 'Subject Count', 'Comments'
                ]);
                
                // CSV Data
                foreach ($reports as $report) {
                    fputcsv($file, [
                        $report->student->id,
                        $report->student->name,
                        $report->average_percentage,
                        $report->grade,
                        $report->total_marks,
                        $report->results->count(),
                        $report->dos_comments
                    ]);
                }
                
                fclose($file);
            };
            
            return Response::stream($callback, 200, $headers);
        } else {
            // Generate PDF (would use a PDF library here)
            // This is simplified and would need a real PDF generator like DOMPDF
            $pdf = app()->make('dompdf.wrapper');
            $pdf->loadView('dos.analytics.pdf', compact('reports', 'year', 'term'));
            
            return $pdf->download('performance_report_' . $year . '_' . $term . '.pdf');
        }
    }
}