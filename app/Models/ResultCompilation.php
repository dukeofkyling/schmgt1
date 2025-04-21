<?php
namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ResultCompilation extends Model
{
    protected $fillable = [
        'student_id', 'term_id', 'compiled_by', 'average_score', 
        'overall_grade', 'overall_position', 'comments', 'submitted_to_head', 'submission_date'
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    
    public function directorOfStudy()
    {
        return $this->belongsTo(DirectorOfStudy::class, 'compiled_by');
    }
}
