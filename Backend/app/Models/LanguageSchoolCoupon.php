<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolCoupon extends Model
{
    use HasFactory;

    protected $table = 'language_school_coupons';

    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'usage_limit',
        'used_count',
        'expiration_date',
        'minimum_purchase_amount',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'usage_limit' => 'int',
        'used_count' => 'int',
        'expiration_date' => 'date',
        'minimum_purchase_amount' => 'decimal:2',
        'is_active' => 'bool',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
