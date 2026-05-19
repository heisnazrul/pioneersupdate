<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_currency',
        'target_currency',
        'fee',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
    ];
}
