<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'student_number', 'class'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject')
                    ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
