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
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'month_num' => 'integer',
    ];

    public function courses()
    {
        return $this->belongsToMany(UniversityCourse::class, 'university_course_intakes')
            ->withPivot(['deadline_date', 'start_date', 'is_active'])
            ->withTimestamps();
    }
}
