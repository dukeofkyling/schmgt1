<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'timetable_path'];


    protected $casts = [
        'publish_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    

}