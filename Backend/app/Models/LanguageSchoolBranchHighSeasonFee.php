<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolBranchHighSeasonFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'week_start',
        'week_end',
        'fee',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'week_start' => 'date',
        'week_end' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }
}
