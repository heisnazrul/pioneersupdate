<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineCourseBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_no',
        'user_id',
        'language_school_id',
        'online_course_id',
        'status',
        'source',
        'assigned_to',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'weeks',
        'start_date',
        'course_fee',
        'registration_fee',
        'subtotal',
        'total_amount',
        'base_currency',
        'display_currency',
        'exchange_rate',
        'conversion_fee_percent',
        'conversion_fee_amount',
        'currency_snapshot',
        'pricing_snapshot',
        'selection_snapshot',
        'referral_code',
        'referral_discount_amount',
        'referrer_type',
        'referrer_user_id',
        'referrer_agent_id',
        'referral_commission_amount',
        'booked_by_agent_id',
        'booked_by_agent_user_id',
        'notes',
        'paid_at',
        'payment_method',
        'payment_reference',
        'payment_proof_path',
        'payment_notes',
        'payment_verified_by',
        'payment_verified_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'course_fee' => 'decimal:2',
        'registration_fee' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'conversion_fee_percent' => 'decimal:2',
        'conversion_fee_amount' => 'decimal:2',
        'currency_snapshot' => 'array',
        'pricing_snapshot' => 'array',
        'selection_snapshot' => 'array',
        'paid_at' => 'datetime',
        'payment_verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(LanguageSchool::class, 'language_school_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(LanguageOnlineCourse::class, 'online_course_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function bookedByAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'booked_by_agent_id');
    }

    public function paymentVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }
}
