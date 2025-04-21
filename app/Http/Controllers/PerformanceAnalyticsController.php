<?php
namespace App\Http\Controllers;

use App\Models\CompiledReport;
use App\Models\Student;
use Illuminate\Http\Request;

class PerformanceAnalyticsController extends Controller
{
    // Display student performance analytics
    public function index()
    {
        $analytics = CompiledReport::with('student')
            ->selectRaw('student_id, term_id, AVG(score) as average_score, COUNT(*) as total_subjects')
            ->groupBy('student_id', 'term_id')
            ->get();

        // Add performance level to the analytics based on average score
        foreach ($analytics as $analytic) {
            if ($analytic->average_score >= 75) {
                $analytic->performance_level = 'Excellent';
            } elseif ($analytic->average_score >= 50) {
                $analytic->performance_level = 'Good';
            } else {
                $analytic->performance_level = 'Needs Improvement';
            }
        }

        return view('performance_analytics.index', compact('analytics'));
    }

    // Detailed analytics for a specific student
    public function show($studentId)
    {
        $student = Student::findOrFail($studentId);

        $analytics = CompiledReport::where('student_id', $studentId)
            ->with('student')
            ->selectRaw('term_id, AVG(score) as average_score, COUNT(*) as total_subjects')
            ->groupBy('term_id')
            ->get();

        return view('performance_analytics.show', compact('student', 'analytics'));
    }
}
