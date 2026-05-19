<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummerCampBooking extends Model
{
    use HasFactory;

    protected $table = 'summer_camps_bookings';

    protected $fillable = [
        'user_id',
        'camp_id',
        'whatsapp',
        'weeks',
        'start_date',
        'special_supplements',
        'final_price',
        'status',
    ];

    protected $casts = [
        'special_supplements' => 'array',
        'start_date' => 'date',
        'final_price' => 'decimal:2',
    ];

    public function camp()
    {
        return $this->belongsTo(LanguageCourseSummerCamp::class, 'camp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
