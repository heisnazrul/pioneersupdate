<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationStat extends Model
{
    use HasFactory;

    protected $fillable = ['destination_id', 'label', 'ar_label', 'value', 'ar_value'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
