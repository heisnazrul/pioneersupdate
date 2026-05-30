<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    public function view(User $user, Quotation $quotation): bool
    {
        if (in_array($user->role, ['admin', 'team'], true)) {
            return true;
        }

        if ($user->role === 'counsellor') {
            return (int) $quotation->assigned_to === $user->id
                || (int) $quotation->created_by === $user->id;
        }

        return false;
    }

    public function update(User $user, Quotation $quotation): bool
    {
        return $this->view($user, $quotation);
    }
}
