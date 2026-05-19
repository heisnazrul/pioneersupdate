<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolSupplement extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'ar_name',
        'amount',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }
}
