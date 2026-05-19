<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationFaq extends Model
{
    use HasFactory;

    protected $fillable = ['destination_id', 'question', 'ar_question', 'answer', 'ar_answer'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
