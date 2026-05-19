<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_name' => 'Pioneers Admissions',
            'site_email' => 'info@pioneers.edu.sa',
            'site_phone' => '+966 50 123 4567',
            'site_address' => 'Riyadh, Saudi Arabia',
            'social_links' => [
                ['platform' => 'Facebook', 'url' => '#'],
                ['platform' => 'Twitter', 'url' => '#'],
                ['platform' => 'Instagram', 'url' => '#'],
                ['platform' => 'LinkedIn', 'url' => '#'],
            ],
            'contact_description' => "Can't make it to an office? No problem. Fill out the form or reach out to us directly through our general channels."
        ];

        foreach ($settings as $key => $value) {
            Setting::put($key, $value);
        }
    }
}
