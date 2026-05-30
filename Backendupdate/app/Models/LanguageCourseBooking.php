<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageCourseBooking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_no',
        'user_id',
        'language_school_id',
        'course_id',
        'status',
        'source',
        'assigned_to',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'weeks',
        'start_date',
        'user_age',
        'accommodation_id',
        'pickup_id',
        'accommodation_weeks',
        'insurance_ids',
        'supplement_ids',
        'selection_snapshot',
        'course_weekly_fee',
        'course_total',
        'accommodation_weekly_fee',
        'accommodation_total',
        'accommodation_waived',
        'accommodation_original',
        'registration_fee',
        'material_fee',
        'mandatory_fee',
        'pickup_fee',
        'pickup_waived',
        'pickup_original',
        'insurance_total',
        'insurance_admin_fee',
        'acc_supplements_total',
        'other_supplements_total',
        'course_discount_percent',
        'course_discount_amount',
        'pioneers_discount_total',
        'coupon_code',
        'coupon_discount_amount',
        'referral_code',
        'referral_discount_amount',
        'referrer_type',
        'referrer_user_id',
        'referrer_agent_id',
        'referral_commission_amount',
        'booked_by_agent_id',
        'booked_by_agent_user_id',
        'subtotal',
        'total_amount',
        'base_currency',
        'display_currency',
        'exchange_rate',
        'conversion_fee_percent',
        'conversion_fee_amount',
        'currency_snapshot',
        'pricing_snapshot',
        'notes',
        'paid_at',
        'payment_method',
        'payment_reference',
        'payment_proof_path',
        'payment_notes',
        'payment_verified_by',
        'payment_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'insurance_ids' => 'array',
            'supplement_ids' => 'array',
            'selection_snapshot' => 'array',
            'currency_snapshot' => 'array',
            'pricing_snapshot' => 'array',
            'accommodation_waived' => 'boolean',
            'pickup_waived' => 'boolean',
            'paid_at' => 'datetime',
            'payment_verified_at' => 'datetime',
        ];
    }

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
        return $this->belongsTo(LanguageSchoolCourse::class, 'course_id');
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolAccommodation::class, 'accommodation_id');
    }

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolPickup::class, 'pickup_id');
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
