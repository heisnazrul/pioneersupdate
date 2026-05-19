<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolCourseMaterialFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_school_course_id',
        'amount',
        'billing_unit',
        'billing_count',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'billing_count' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(LanguageSchoolCourse::class, 'language_school_course_id');
    }
}
