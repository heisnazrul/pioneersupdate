<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $countryId = 1; // UK

        $cities = [
            ['name' => 'Aberdeen', 'ar_name' => 'أبردين'],
            ['name' => 'Aberystwyth', 'ar_name' => 'أبيريستويث'],
            ['name' => 'Bangor', 'ar_name' => 'بانغور'],
            ['name' => 'Bath', 'ar_name' => 'باث'],
            ['name' => 'Bedfordshire', 'ar_name' => 'بيدفوردشير'],
            ['name' => 'Belfast', 'ar_name' => 'بلفاست'],
            ['name' => 'Birmingham', 'ar_name' => 'برمنغهام'],
            ['name' => 'Bournemouth', 'ar_name' => 'بورنموث'],
            ['name' => 'Bradford', 'ar_name' => 'برادفورد'],
            ['name' => 'Brighton', 'ar_name' => 'برايتون'],
            ['name' => 'Bristol', 'ar_name' => 'بريستول'],
            ['name' => 'Cambridge', 'ar_name' => 'كامبردج'],
            ['name' => 'Canterbury', 'ar_name' => 'كانتربري'],
            ['name' => 'Kent', 'ar_name' => 'كنت'],
            ['name' => 'Cardiff', 'ar_name' => 'كارديف'],
            ['name' => 'Colchester', 'ar_name' => 'كولتشستر'],
            ['name' => 'Coventry', 'ar_name' => 'كوفنتري'],
            ['name' => 'Derby', 'ar_name' => 'ديربي'],
            ['name' => 'Dundee', 'ar_name' => 'دندي'],
            ['name' => 'Durham', 'ar_name' => 'دورهام'],
            ['name' => 'Edinburgh', 'ar_name' => 'إدنبرة'],
            ['name' => 'Egham', 'ar_name' => 'إغهام'],
            ['name' => 'Exeter', 'ar_name' => 'إكستر'],
            ['name' => 'Glasgow', 'ar_name' => 'غلاسكو'],
            ['name' => 'Guildford', 'ar_name' => 'غيلدفورد'],
            ['name' => 'Hatfield', 'ar_name' => 'هاتفيلد'],
            ['name' => 'Huddersfield', 'ar_name' => 'هديرسفيلد'],
            ['name' => 'Hull', 'ar_name' => 'هَل'],
            ['name' => 'Keele', 'ar_name' => 'كيل'],
            ['name' => 'Kingston upon Thames', 'ar_name' => 'كنغستون أبون تيمز'],
            ['name' => 'Lancaster', 'ar_name' => 'لانكستر'],
            ['name' => 'Leeds', 'ar_name' => 'ليدز'],
            ['name' => 'Leicester', 'ar_name' => 'ليستر'],
            ['name' => 'Lincoln', 'ar_name' => 'لينكولن'],
            ['name' => 'Liverpool', 'ar_name' => 'ليفربول'],
            ['name' => 'London', 'ar_name' => 'لندن'],
            ['name' => 'Loughborough', 'ar_name' => 'لافبرا'],
            ['name' => 'Manchester', 'ar_name' => 'مانشستر'],
            ['name' => 'Middlesbrough', 'ar_name' => 'ميدلزبره'],
            ['name' => 'Milton Keynes', 'ar_name' => 'ميلتون كينز'],
            ['name' => 'Newcastle upon Tyne', 'ar_name' => 'نيوكاسل أبون تاين'],
            ['name' => 'Newport', 'ar_name' => 'نيوبورت (شروبشاير)'],
            ['name' => 'Norwich', 'ar_name' => 'نورويتش'],
            ['name' => 'Northampton', 'ar_name' => 'نورثهامبتون'],
            ['name' => 'Nottingham', 'ar_name' => 'نوتنغهام'],
            ['name' => 'Ormskirk', 'ar_name' => 'أورمسكيرك'],
            ['name' => 'Oxford', 'ar_name' => 'أكسفورد'],
            ['name' => 'Plymouth', 'ar_name' => 'بليموث'],
            ['name' => 'Portsmouth', 'ar_name' => 'بورتسموث'],
            ['name' => 'Preston', 'ar_name' => 'برستون'],
            ['name' => 'Reading', 'ar_name' => 'ريدينغ'],
            ['name' => 'Salford', 'ar_name' => 'سالفورد'],
            ['name' => 'Sheffield', 'ar_name' => 'شيفيلد'],
            ['name' => 'Southampton', 'ar_name' => 'ساوثهامبتون'],
            ['name' => 'St Andrews', 'ar_name' => 'سانت أندروز'],
            ['name' => 'Stirling', 'ar_name' => 'ستيرلينغ'],
            ['name' => 'Swansea', 'ar_name' => 'سوانزي'],
            ['name' => 'Uxbridge', 'ar_name' => 'أكسبريدج'],
            ['name' => 'Wolverhampton', 'ar_name' => 'وولفرهامبتون'],
            ['name' => 'York', 'ar_name' => 'يورك'],
        ];

        foreach ($cities as $index => $city) {
            City::updateOrCreate(
                [
                    'name' => $city['name'],
                    'country_id' => $countryId,
                ],
                [
                    'ar_name' => $city['ar_name'],
                    'description' => "{$city['name']} is one of the most popular cities in the United Kingdom.",
                    'ar_description' => "{$city['ar_name']} من أشهر المدن في المملكة المتحدة.",
                    'country_id' => $countryId,
                    'display_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
