<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguageCourseSummerCampDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'overview',
        'ar_overview',
        'academics',
        'ar_academics',
        'activities',
        'ar_activities',
        'accommodation',
        'ar_accommodation',
        'safeguarding',
        'ar_safeguarding',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function camp(): BelongsTo
    {
        return $this->belongsTo(LanguageCourseSummerCamp::class, 'camp_id');
    }
}
