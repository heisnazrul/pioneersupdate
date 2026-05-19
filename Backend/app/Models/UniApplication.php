<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniApplication extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'email',
        'phone',
        'intake',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(UniversityCourse::class, 'course_id');
    }
}
