<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageCourseBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'accommodation_id',
        'pickup_id',
        'insurance_id',
        'whatsapp',
        'user_age',
        'weeks',
        'start_date',
        'accommodation_weeks',
        'supplements_ids',
        'final_price',
        'currency',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'supplements_ids' => 'array',
        'start_date' => 'date',
        'final_price' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(LanguageSchoolCourse::class, 'course_id');
    }

    public function accommodation()
    {
        return $this->belongsTo(LanguageSchoolAccommodation::class, 'accommodation_id');
    }

    public function pickup()
    {
        return $this->belongsTo(LanguageSchoolPickup::class, 'pickup_id');
    }

    public function insurance()
    {
        return $this->belongsTo(LanguageSchoolInsuranceFee::class, 'insurance_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
