<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CountryFlagUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_svg_country_flag(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        $country = Country::create([
            'name' => 'United Kingdom',
            'ar_name' => 'المملكة المتحدة',
            'slug' => 'united-kingdom',
            'country_code' => 'GB',
            'currency_code' => 'GBP',
            'is_active' => true,
        ]);

        $svg = UploadedFile::fake()->createWithContent(
            'gb.svg',
            '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"></svg>',
            'image/svg+xml',
        );

        $response = $this->actingAs($admin)->put(route('admin.countries.update', $country), [
            'name' => $country->name,
            'ar_name' => $country->ar_name,
            'country_code' => 'GB',
            'currency_code' => 'GBP',
            'is_active' => '1',
            'flag' => $svg,
        ]);

        $response->assertRedirect(route('admin.countries.index'));

        $country->refresh();
        $this->assertNotNull($country->flag);
        $this->assertStringStartsWith('flags/', $country->flag);
        Storage::disk('public')->assertExists($country->flag);
    }
}
