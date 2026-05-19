<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguageSchoolInsurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'weekly_fee',
        'admin_fee',
        'is_mandatory'
    ];

    protected $casts = [
        'weekly_fee' => 'decimal:2',
        'admin_fee'  => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }
}
