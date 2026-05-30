<?php

namespace App\Support;

final class BookingStatus
{
    public const PENDING = 'pending';

    public const CONFIRMED = 'confirmed';

    public const PAID = 'paid';

    public const COMPLETED = 'completed';

    public const CANCELLED = 'cancelled';

    public const REJECTED = 'rejected';

    public const ALL = [
        self::PENDING,
        self::CONFIRMED,
        self::PAID,
        self::COMPLETED,
        self::CANCELLED,
        self::REJECTED,
    ];

    public static function label(string $status): string
    {
        return ucfirst(str_replace('_', ' ', $status));
    }
}
