<?php
namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'subject_id', 'term_id', 'marks', 'grade', 
        'comments', 'submitted_by', 'approved_by_dos', 'submitted_to_head'
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
// Result.php
public function subject()
{
    return $this->belongsTo(Subject::class);
}


    
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'submitted_by');
    }
}