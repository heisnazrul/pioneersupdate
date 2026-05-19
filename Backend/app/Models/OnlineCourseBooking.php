<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'whatsapp',
        'weeks',
        'start_date',
        'final_price',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'final_price' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(LanguageCourseOnlineCourse::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
