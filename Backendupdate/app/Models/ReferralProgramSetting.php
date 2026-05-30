<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralProgramSetting extends Model
{
    protected $fillable = [
        'scope',
        'discount_type',
        'discount_value',
        'commission_type',
        'commission_value',
        'min_booking_amount',
        'max_discount_amount',
        'max_commission_amount',
        'cookie_ttl_days',
        'attribution_window_days',
        'applies_to',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'applies_to' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
