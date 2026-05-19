<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'contacted', 'resolved'];

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status',
    ];
}
