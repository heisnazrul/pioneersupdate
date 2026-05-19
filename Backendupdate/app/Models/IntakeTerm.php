<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntakeTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'ar_name',
        'month_num',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'month_num' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function courses()
    {
        return $this->belongsToMany(UniversityCourse::class, 'university_course_intakes')
            ->withPivot(['deadline_date', 'start_date', 'is_active'])
            ->withTimestamps();
    }
}
