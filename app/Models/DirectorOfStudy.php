<?php
namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DirectorOfStudy extends Model
{
    protected $fillable = ['user_id', 'name', 'contact_info'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'created_by');
    }
    
    public function resultCompilations()
    {
        return $this->hasMany(ResultCompilation::class, 'compiled_by');
    }
    
    public function circulars()
    {
        return $this->hasMany(Circular::class, 'created_by');
    }
}
