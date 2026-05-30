<?php

namespace App\Support;

class QuotationStatus
{
    public const DRAFT = 'draft';

    public const SENT = 'sent';

    public const ACCEPTED = 'accepted';

    public const EXPIRED = 'expired';

    public const CONVERTED = 'converted';

    public const CANCELLED = 'cancelled';

    public const ALL = [
        self::DRAFT,
        self::SENT,
        self::ACCEPTED,
        self::EXPIRED,
        self::CONVERTED,
        self::CANCELLED,
    ];
}
