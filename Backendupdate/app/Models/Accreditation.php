<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Accreditation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ar_name', 'logo'];

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(
            LanguageSchoolBranch::class,
            'accreditation_language_school_branch',
            'accreditation_id',
            'language_school_branch_id'
        )->withTimestamps();
    }
}
