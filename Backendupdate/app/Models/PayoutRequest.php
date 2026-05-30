<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutRequest extends Model
{
    public const STATUSES = ['pending', 'approved', 'paid', 'rejected', 'cancelled'];

    protected $fillable = [
        'reference_no',
        'user_id',
        'agent_id',
        'referrer_type',
        'amount',
        'currency',
        'status',
        'bank_account_name',
        'bank_name',
        'bank_account_number',
        'bank_iban',
        'bank_swift_code',
        'user_notes',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
