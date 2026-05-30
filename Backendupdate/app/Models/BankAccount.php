<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'logo_path',
        'logo_text',
        'beneficiary_en',
        'beneficiary_ar',
        'account_number',
        'iban',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 'yes');
    }
}
