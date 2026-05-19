<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolPioneersDiscount extends Model
{
    use HasFactory;

    protected $table = 'language_school_pioneers_discounts';

    protected $fillable = [
        'name',
        'ar_name',
        'weeks',
        'discount_amount',
        'discount_full_for',
        'is_active',
    ];

    protected $casts = [
        'weeks' => 'int',
        'discount_amount' => 'decimal:2',
        'is_active' => 'bool',
    ];
}
