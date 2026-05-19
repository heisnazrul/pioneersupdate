<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $countryIds = DB::table('countries')->pluck('id', 'country_code');

        $groups = [
            'GB' => [
                ['London', 'لندن'], ['Manchester', 'مانشستر'], ['Birmingham', 'برمنغهام'], ['Liverpool', 'ليفربول'],
                ['Leeds', 'ليدز'], ['Glasgow', 'غلاسكو'], ['Edinburgh', 'إدنبرة'], ['Bristol', 'بريستول'],
                ['Sheffield', 'شيفيلد'], ['Newcastle upon Tyne', 'نيوكاسل أبون تاين'], ['Nottingham', 'نوتنغهام'],
                ['Leicester', 'ليستر'], ['Coventry', 'كوفنتري'], ['Cardiff', 'كارديف'], ['Belfast', 'بلفاست'],
                ['Brighton', 'برايتون'], ['Southampton', 'ساوثهامبتون'], ['Reading', 'ريدينغ'], ['Oxford', 'أكسفورد'],
                ['Cambridge', 'كامبردج'], ['Bath', 'باث'], ['York', 'يورك'], ['Exeter', 'إكستر'],
                ['Bournemouth', 'بورنموث'], ['Lancaster', 'لانكستر'], ['Durham', 'دورهام'], ['Guildford', 'غيلدفورد'],
                ['Hatfield', 'هاتفيلد'], ['Aberdeen', 'أبردين'], ['Loughborough', 'لافبرا'], ['St Andrews', 'سانت أندروز'],
                ['Aberystwyth', 'أبيريستويث'], ['Milton Keynes', 'ميلتون كينز'], ['Ormskirk', 'أورمسكيرك'], ['Middlesbrough', 'ميدلزبره'],
                ['Salford', 'سالفورد'], ['Wolverhampton', 'وولفرهامبتون'], ['Derby', 'ديربي'], ['Northampton', 'نورثهامبتون'],
                ['Newport', 'نيوبورت'], ['Stirling', 'ستيرلينغ'], ['Canterbury', 'كانتربري'], ['Keele', 'كيل'],
            ],
            'US' => [['New York', 'نيويورك'], ['Los Angeles', 'لوس أنجلوس'], ['Chicago', 'شيكاغو']],
            'CA' => [['Toronto', 'تورونتو'], ['Vancouver', 'فانكوفر'], ['Montreal', 'مونتريال']],
            'AU' => [['Sydney', 'سيدني'], ['Melbourne', 'ملبورن'], ['Brisbane', 'بريسبان']],
            'DE' => [['Berlin', 'برلين'], ['Munich', 'ميونخ'], ['Frankfurt', 'فرانكفورت']],
            'IE' => [['Dublin', 'دبلن'], ['Cork', 'كورك'], ['Galway', 'غالواي']],
            'NL' => [['Amsterdam', 'أمستردام'], ['Rotterdam', 'روتردام'], ['Utrecht', 'أوتريخت']],
            'MY' => [['Kuala Lumpur', 'كوالالمبور'], ['Penang', 'بينانغ'], ['Johor Bahru', 'جوهور باهرو']],
            'SA' => [['Riyadh', 'الرياض'], ['Jeddah', 'جدة'], ['Dammam', 'الدمام']],
            'BD' => [['Dhaka', 'دكا'], ['Chittagong', 'شيتاغونغ'], ['Sylhet', 'سيلهيت']],
        ];

        foreach ($groups as $countryCode => $cities) {
            $countryId = $countryIds[$countryCode] ?? null;
            if (! $countryId) {
                continue;
            }

            foreach ($cities as $index => [$name, $arName]) {
                DB::table('cities')->updateOrInsert(
                    ['country_id' => $countryId, 'name' => $name],
                    [
                        'ar_name' => $arName,
                        'slug' => Str::slug($name),
                        'description' => $name . ' is one of the supported cities in the platform.',
                        'ar_description' => $arName . ' من المدن المدعومة في المنصة.',
                        'latitude' => null,
                        'longitude' => null,
                        'display_order' => $index + 1,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
