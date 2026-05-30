<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'United Kingdom', 'ar_name' => 'المملكة المتحدة', 'country_code' => 'GB', 'currency_code' => 'GBP', 'phone_code' => '+44', 'capital' => 'London', 'continent' => 'Europe', 'is_popular' => true, 'display_order' => 1],
            ['name' => 'United States', 'ar_name' => 'الولايات المتحدة', 'country_code' => 'US', 'currency_code' => 'USD', 'phone_code' => '+1', 'capital' => 'Washington, D.C.', 'continent' => 'North America', 'is_popular' => true, 'display_order' => 2],
            ['name' => 'Canada', 'ar_name' => 'كندا', 'country_code' => 'CA', 'currency_code' => 'CAD', 'phone_code' => '+1', 'capital' => 'Ottawa', 'continent' => 'North America', 'is_popular' => true, 'display_order' => 3],
            ['name' => 'Australia', 'ar_name' => 'أستراليا', 'country_code' => 'AU', 'currency_code' => 'AUD', 'phone_code' => '+61', 'capital' => 'Canberra', 'continent' => 'Oceania', 'is_popular' => true, 'display_order' => 4],
            ['name' => 'Germany', 'ar_name' => 'ألمانيا', 'country_code' => 'DE', 'currency_code' => 'EUR', 'phone_code' => '+49', 'capital' => 'Berlin', 'continent' => 'Europe', 'is_popular' => true, 'display_order' => 5],
            ['name' => 'Ireland', 'ar_name' => 'أيرلندا', 'country_code' => 'IE', 'currency_code' => 'EUR', 'phone_code' => '+353', 'capital' => 'Dublin', 'continent' => 'Europe', 'is_popular' => true, 'display_order' => 6],
            ['name' => 'Netherlands', 'ar_name' => 'هولندا', 'country_code' => 'NL', 'currency_code' => 'EUR', 'phone_code' => '+31', 'capital' => 'Amsterdam', 'continent' => 'Europe', 'is_popular' => true, 'display_order' => 7],
            ['name' => 'Malaysia', 'ar_name' => 'ماليزيا', 'country_code' => 'MY', 'currency_code' => 'MYR', 'phone_code' => '+60', 'capital' => 'Kuala Lumpur', 'continent' => 'Asia', 'is_popular' => true, 'display_order' => 8],
            ['name' => 'Saudi Arabia', 'ar_name' => 'المملكة العربية السعودية', 'country_code' => 'SA', 'currency_code' => 'SAR', 'phone_code' => '+966', 'capital' => 'Riyadh', 'continent' => 'Asia', 'is_popular' => true, 'display_order' => 9],
            ['name' => 'Bangladesh', 'ar_name' => 'بنغلاديش', 'country_code' => 'BD', 'currency_code' => 'BDT', 'phone_code' => '+880', 'capital' => 'Dhaka', 'continent' => 'Asia', 'is_popular' => true, 'display_order' => 10],
        ];

        foreach ($rows as $row) {
            $exists = DB::table('countries')->where('country_code', $row['country_code'])->exists();

            $payload = [
                'name' => $row['name'],
                'ar_name' => $row['ar_name'],
                'slug' => Str::slug($row['name']),
                'is_popular' => $row['is_popular'],
                'currency_code' => $row['currency_code'],
                'phone_code' => $row['phone_code'],
                'description' => $row['name'] . ' is an important destination in the platform.',
                'ar_description' => $row['ar_name'] . ' من الوجهات المهمة في المنصة.',
                'capital' => $row['capital'],
                'continent' => $row['continent'],
                'display_order' => $row['display_order'],
                'is_active' => true,
                'updated_at' => now(),
            ];

            if (! $exists) {
                $payload['flag'] = null;
                $payload['created_at'] = now();
            }

            DB::table('countries')->updateOrInsert(
                ['country_code' => $row['country_code']],
                $payload
            );
        }

        $withDefault = Country::query()
            ->whereIn('country_code', collect($rows)->pluck('country_code'))
            ->get()
            ->filter(fn (Country $country) => $country->resolveFlagPath() !== null)
            ->count();

        $this->command?->info("Countries seeded. {$withDefault}/" . count($rows) . ' have default flags in storage/app/public/flags/.');
    }
}
