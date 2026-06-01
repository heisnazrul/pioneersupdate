<?php

namespace Database\Seeders;

use App\Models\LanguageSchool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LanguageSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            ['name_en' => 'Anglo Continental', 'name_ar' => 'معهد أنجلو كونتيننتال', 'has_online' => 'yes'],
            ['name_en' => 'ATLAS Language School', 'name_ar' => 'معهد أطلس لانجويج', 'has_online' => 'no'],
            ['name_en' => 'Bright School Of English', 'name_ar' => 'معهد برايت للغة الإنجليزية', 'has_online' => 'no'],
            ['name_en' => 'CELT', 'name_ar' => 'معهد سي إي إل تي', 'has_online' => 'yes'],
            ['name_en' => 'Central Language School', 'name_ar' => 'معهد سنترال للغات', 'has_online' => 'no'],
            ['name_en' => 'Eurospeak Language School', 'name_ar' => 'معهد يوروسبيك لتعليم اللغات', 'has_online' => 'no'],
            ['name_en' => 'LSI', 'name_ar' => 'معهد إل إس آي', 'has_online' => 'yes'],
            ['name_en' => 'Lewis School of English', 'name_ar' => 'معهد لويس', 'has_online' => 'yes'],
            ['name_en' => 'NCG New College Group', 'name_ar' => 'معهد نيو كوليدج قروب للغة الانجليزية', 'has_online' => 'yes'],
            ['name_en' => 'Southbourne School of English', 'name_ar' => 'معهد ساوثبورن للغة الإنجليزية', 'has_online' => 'no'],
            ['name_en' => 'Stafford House', 'name_ar' => 'معهد ستافورد هاوس', 'has_online' => 'no'],
            ['name_en' => 'Future Learning Language School', 'name_ar' => 'معهد فيوتشر ليرنينق للغات', 'has_online' => 'yes'],
            ['name_en' => 'Inlingua', 'name_ar' => 'معهد إنلينجوا شلتنهام', 'has_online' => 'yes'],
            ['name_en' => 'Kings Hall College', 'name_ar' => 'كينجز هول كوليدج', 'has_online' => 'yes'],
            ['name_en' => 'Bayswater', 'name_ar' => 'معهد بايزووتر', 'has_online' => 'yes'],
            ['name_en' => 'Regent', 'name_ar' => 'معهد ريجنت', 'has_online' => 'yes'],
            ['name_en' => 'The Essential English Centre', 'name_ar' => 'معهد إسينشال إنجلش سنتر', 'has_online' => 'no'],
            ['name_en' => 'Basil Paterson Edinburgh', 'name_ar' => 'معهد باسل باترسون', 'has_online' => 'yes'],
            ['name_en' => 'Suzanne Sparrow Plymouth Language School', 'name_ar' => 'معهد سوزان سبارو', 'has_online' => 'no'],
            ['name_en' => 'Studio Cambridge', 'name_ar' => 'معهد ستوديو كامبريدج', 'has_online' => 'no'],
        ];

        foreach ($schools as $school) {
            LanguageSchool::updateOrCreate(
                ['slug' => Str::slug($school['name_en'])],
                [
                    'name_en' => $school['name_en'],
                    'name_ar' => $school['name_ar'],
                    'logo_url' => null,
                    'has_online' => $school['has_online'],
                    'status' => 'active',
                ]
            );
        }

        $this->command?->info('Seeded ' . count($schools) . ' language schools.');
    }
}
