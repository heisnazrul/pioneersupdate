<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CountryFlagFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_country_uses_default_flag_from_storage_when_not_uploaded(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('flags/gb.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>');

        $country = Country::create([
            'name' => 'United Kingdom',
            'ar_name' => 'UK',
            'slug' => 'united-kingdom',
            'country_code' => 'GB',
            'currency_code' => 'GBP',
            'is_active' => true,
        ]);

        $this->assertNull($country->flag);
        $this->assertSame('flags/gb.svg', $country->resolveFlagPath());
    }

    public function test_uploaded_flag_takes_priority_over_default(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('flags/gb.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>');
        Storage::disk('public')->put('flags/custom-gb.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>');

        $country = Country::create([
            'name' => 'United Kingdom',
            'ar_name' => 'UK',
            'slug' => 'uk-custom',
            'country_code' => 'GB',
            'currency_code' => 'GBP',
            'flag' => 'flags/custom-gb.svg',
            'is_active' => true,
        ]);

        $this->assertSame('flags/custom-gb.svg', $country->resolveFlagPath());
    }
}
