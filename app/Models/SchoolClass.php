<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes'; // Important if table is named `classes`
    protected $fillable = ['name'];

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }
    
}

