<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolCourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_school_course_id',
        'week_number',
        'fee',
        'valid_from',
        'valid_to',
        'price_split',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(LanguageSchoolCourse::class, 'language_school_course_id');
    }
}
