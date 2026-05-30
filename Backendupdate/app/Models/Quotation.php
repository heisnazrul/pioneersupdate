<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_SENT = 'sent';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_CONVERTED = 'converted';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SENT,
        self::STATUS_ACCEPTED,
        self::STATUS_EXPIRED,
        self::STATUS_CONVERTED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'reference_no',
        'status',
        'booking_type',
        'student_user_id',
        'created_by',
        'assigned_to',
        'language_school_id',
        'course_id',
        'selection_snapshot',
        'pricing_snapshot',
        'currency_snapshot',
        'total_amount',
        'display_currency',
        'valid_until',
        'notes',
        'converted_booking_type',
        'converted_booking_id',
    ];

    protected function casts(): array
    {
        return [
            'selection_snapshot' => 'array',
            'pricing_snapshot' => 'array',
            'currency_snapshot' => 'array',
            'valid_until' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(LanguageSchool::class, 'language_school_id');
    }
}
