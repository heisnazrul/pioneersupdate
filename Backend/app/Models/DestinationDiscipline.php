<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationDiscipline extends Model
{
    use HasFactory;

    protected $fillable = ['destination_id', 'discipline', 'ar_discipline'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
