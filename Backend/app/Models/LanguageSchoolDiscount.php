<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolDiscount extends Model
{
    use HasFactory;

    protected $table = 'language_school_discounts';

    protected $fillable = [
        'name',
        'ar_name',
        'discount_percentage',
        'applies_to_all_branches',
        'applies_to_all_countries',
        'school_branch_ids',
        'country_ids',
        'applies_to_user_country',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'applies_to_all_branches' => 'bool',
        'applies_to_all_countries' => 'bool',
        'applies_to_user_country' => 'bool',
        'school_branch_ids' => 'array',
        'country_ids' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'bool',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
