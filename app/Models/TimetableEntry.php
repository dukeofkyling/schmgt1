<?php
namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TimetableEntry extends Model
{
    protected $fillable = [
        'timetable_id', 'day_of_week', 'start_time', 'end_time', 
        'subject_id', 'teacher_id', 'room'
    ];
    
    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}