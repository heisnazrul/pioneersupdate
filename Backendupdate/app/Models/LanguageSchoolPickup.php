<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguageSchoolPickup extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'pickup_location',
        'fee'
    ];

    protected $casts = [
        'fee' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }
}
