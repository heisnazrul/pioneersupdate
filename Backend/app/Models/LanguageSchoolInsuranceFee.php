<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolInsuranceFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'ar_name',
        'amount',
        'admin_charge',
        'billing_unit',
        'billing_count',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'admin_charge' => 'decimal:2',
        'billing_count' => 'integer',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }
}
