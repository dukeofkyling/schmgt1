<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
class Teacher extends Model
{
    protected $fillable = ['name', 'email', 'phone_number', 'employment_date', 'subject_id'];
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function results()
    {
        return $this->hasMany(StudentResult::class, 'submitted_by');
    }
}