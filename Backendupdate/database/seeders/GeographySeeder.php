<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\City;
use Illuminate\Support\Str;

class GeographySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Australia', 'ar_name' => 'أستراليا', 'code' => 'AU', 'currency' => 'AUD', 'cities' => ['Sydney', 'Melbourne', 'Brisbane']],
            ['name' => 'Canada', 'ar_name' => 'كندا', 'code' => 'CA', 'currency' => 'CAD', 'cities' => ['Toronto', 'Vancouver', 'Montreal']],
            ['name' => 'France', 'ar_name' => 'فرنسا', 'code' => 'FR', 'currency' => 'EUR', 'cities' => ['Paris', 'Lyon', 'Nice']],
            ['name' => 'Ireland', 'ar_name' => 'أيرلندا', 'code' => 'IE', 'currency' => 'EUR', 'cities' => ['Dublin', 'Cork', 'Galway']],
            ['name' => 'Malta', 'ar_name' => 'مالطا', 'code' => 'MT', 'currency' => 'EUR', 'cities' => ['Valletta', 'Sliema', 'St. Julians']],
            ['name' => 'New Zealand', 'ar_name' => 'نيوزيلندا', 'code' => 'NZ', 'currency' => 'NZD', 'cities' => ['Auckland', 'Wellington', 'Christchurch']],
            ['name' => 'Russia', 'ar_name' => 'روسيا', 'code' => 'RU', 'currency' => 'RUB', 'cities' => ['Moscow', 'Saint Petersburg', 'Kazan']],
            ['name' => 'Saudi Arabia', 'ar_name' => 'المملكة العربية السعودية', 'code' => 'SA', 'currency' => 'SAR', 'cities' => ['Riyadh', 'Jeddah', 'Dammam']],
            ['name' => 'South Africa', 'ar_name' => 'جنوب أفريقيا', 'code' => 'ZA', 'currency' => 'ZAR', 'cities' => ['Cape Town', 'Johannesburg', 'Durban']],
            ['name' => 'Switzerland', 'ar_name' => 'سويسرا', 'code' => 'CH', 'currency' => 'CHF', 'cities' => ['Zurich', 'Geneva', 'Bern']],
            ['name' => 'United Kingdom', 'ar_name' => 'المملكة المتحدة', 'code' => 'GB', 'currency' => 'GBP', 'cities' => ['London', 'Manchester', 'Birmingham', 'Cambridge'], 'aux' => 'UK', 'ar_aux' => 'بريطانيا'],
            ['name' => 'United States', 'ar_name' => 'الولايات المتحدة', 'code' => 'US', 'currency' => 'USD', 'cities' => ['New York', 'Los Angeles', 'Chicago'], 'aux' => 'USA', 'ar_aux' => 'أمريكا'],
            ['name' => 'Bangladesh', 'ar_name' => 'بنجلاديش', 'code' => 'BD', 'currency' => 'BDT', 'cities' => ['Dhaka', 'Chittagong', 'Sylhet']],
        ];

        foreach ($data as $c) {
            $country = Country::updateOrCreate(
                ['country_code' => $c['code']],
                [
                    'name' => $c['name'],
                    'ar_name' => $c['ar_name'],
                    'slug' => Str::slug($c['name']),
                    'currency_code' => $c['currency'],
                    'auxiliary_name' => $c['aux'] ?? null,
                    'ar_auxiliary_name' => $c['ar_aux'] ?? null,
                    'is_active' => true,
                ]
            );

            foreach ($c['cities'] as $cityName) {
                City::updateOrCreate(
                    ['country_id' => $country->id, 'name' => $cityName],
                    [
                        'ar_name' => $cityName, // Using English name for Arabic too for now as a placeholder
                        'slug' => Str::slug($cityName),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
