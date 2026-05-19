<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationIntake extends Model
{
    use HasFactory;

    protected $fillable = ['destination_id', 'month', 'ar_month', 'event', 'ar_event'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
