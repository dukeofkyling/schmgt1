<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class CompiledReport extends Model
{
    protected $fillable = [
        'student_id',
        'term_id',
        'compiled_by',
        'summary', // or 'report_data' depending on what you use
        'compiled_at',
        'status',
    ];

    protected $casts = [
        'summary' => 'array',
        'compiled_at' => 'datetime',
    ];
}

