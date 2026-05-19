<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageCourseCompare extends Model
{
    protected $fillable = ['user_id', 'course_type', 'course_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
