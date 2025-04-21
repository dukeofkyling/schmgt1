<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    
    public function create()
    {
        $subjects = Subject::all(); // Assuming you have a Subject model
        return view('teachers.create', compact('subjects'));
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
    
    public function students()
{
    return $this->belongsToMany(Student::class);
}

    public function timetableEntries()
    {
        return $this->hasMany(TimetableEntry::class);
    }
}
