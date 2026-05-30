<?php

namespace App\Services\Profile;

use App\Models\Agent;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Models\UserProfile;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileService
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
    ) {
    }

    public function profileRules(User $user, bool $allowPassword = true): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'postal_code' => ['nullable', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:64'],
            'alt_phone' => ['nullable', 'string', 'max:50'],
            'bank_account_name' => ['nullable', 'string', 'max:191'],
            'bank_name' => ['nullable', 'string', 'max:191'],
            'bank_account_number' => ['nullable', 'string', 'max:64'],
            'bank_iban' => ['nullable', 'string', 'max:64'],
            'bank_swift_code' => ['nullable', 'string', 'max:32'],
            'avatar' => ['nullable', 'image', 'max:4096'],
        ];

        if ($allowPassword) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function update(User $user, array $data, ?Request $request = null): User
    {
        $user->fill([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($request?->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        $profile = $user->profile ?: new UserProfile(['user_id' => $user->id]);
        $country = ! empty($data['country']) ? $this->findCountry($data['country']) : null;
        $city = ! empty($data['city']) ? $this->findCity($data['city']) : null;

        $profile->fill([
            'date_of_birth' => $data['birth_date'] ?? $profile->date_of_birth,
            'gender' => $data['gender'] ?? $profile->gender,
            'current_country_id' => $country?->id ?? $profile->current_country_id,
            'current_city_id' => $city?->id ?? $profile->current_city_id,
            'address_line' => $data['address'] ?? $profile->address_line,
            'postal_code' => $data['postal_code'] ?? $profile->postal_code,
            'national_id' => $data['national_id'] ?? $profile->national_id,
            'alt_phone_e164' => $data['alt_phone'] ?? $profile->alt_phone_e164,
            'bank_account_name' => $data['bank_account_name'] ?? $profile->bank_account_name,
            'bank_name' => $data['bank_name'] ?? $profile->bank_name,
            'bank_account_number' => $data['bank_account_number'] ?? $profile->bank_account_number,
            'bank_iban' => $data['bank_iban'] ?? $profile->bank_iban,
            'bank_swift_code' => $data['bank_swift_code'] ?? $profile->bank_swift_code,
        ]);
        $profile->save();

        return $user->fresh([
            'profile.nationalityCountry',
            'profile.currentCountry',
            'profile.currentCity',
            'roles',
            'agent',
        ]);
    }

    public function payload(User $user, ?Agent $agent = null): array
    {
        $profile = $user->profile;
        $isAgent = $agent instanceof Agent || $user->hasRole('lg_agent');

        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->primaryFrontendRoleForApp(User::APP_COURSEENGLISH) ?: $user->role,
            'avatar' => $this->support->toPublicUrl($user->avatar),
            'status' => $user->status,
            'birth_date' => optional($profile?->date_of_birth)->format('Y-m-d'),
            'gender' => $profile?->gender,
            'country' => $profile?->currentCountry?->name,
            'city' => $profile?->currentCity?->name,
            'address' => $profile?->address_line,
            'postal_code' => $profile?->postal_code,
            'national_id' => $profile?->national_id,
            'alt_phone' => $profile?->alt_phone_e164,
            'bank_account_name' => $profile?->bank_account_name,
            'bank_name' => $profile?->bank_name,
            'bank_account_number' => $profile?->bank_account_number,
            'bank_iban' => $profile?->bank_iban,
            'bank_swift_code' => $profile?->bank_swift_code,
            'commission_balance' => $isAgent
                ? (float) ($agent?->commission_balance ?? $user->agent?->commission_balance ?? 0)
                : (float) ($user->referral_commission_balance ?? 0),
            'commission_currency' => 'SAR',
            'has_commission_balance' => $isAgent
                ? (float) ($agent?->commission_balance ?? $user->agent?->commission_balance ?? 0) > 0
                : (float) ($user->referral_commission_balance ?? 0) > 0,
        ];

        if ($agent instanceof Agent) {
            $payload = array_merge($payload, [
                'agent_id' => $agent->id,
                'company_name' => $agent->company_name,
                'agent_status' => $agent->status,
                'referral_code' => $agent->referral_code,
                'referral_discount' => $agent->referral_discount,
                'commission_percent' => $agent->commission_percent,
                'total_commission_earned' => (float) ($agent->total_commission_earned ?? 0),
            ]);
        }

        return $payload;
    }

    public function hasBankDetails(User $user): bool
    {
        $profile = $user->profile;

        return $profile
            && filled($profile->bank_account_name)
            && filled($profile->bank_name)
            && (filled($profile->bank_account_number) || filled($profile->bank_iban));
    }

    private function findCountry(string $value): ?Country
    {
        return Country::query()
            ->where('name', $value)
            ->orWhere('ar_name', $value)
            ->orWhere('slug', Str::slug($value))
            ->first();
    }

    private function findCity(string $value): ?City
    {
        return City::query()
            ->where('name', $value)
            ->orWhere('ar_name', $value)
            ->orWhere('slug', Str::slug($value))
            ->first();
    }
}
