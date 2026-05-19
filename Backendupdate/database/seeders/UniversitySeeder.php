<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        $countryIds = DB::table('countries')->pluck('id', 'country_code');
        $ukId = $countryIds['GB'] ?? null;

        if (! $ukId) {
            return;
        }

        $cityIds = DB::table('cities')
            ->where('country_id', $ukId)
            ->pluck('id', 'name');

        $rows = [
            ['name' => 'Imperial College London', 'slug' => 'imperial-college-london', 'city' => 'London', 'qs_ranking' => 2, 'the_ranking' => 8, 'shanghai_ranking' => 26, 'famous_for' => 'Engineering, Sciences, Medicine, Technology', 'ar_famous_for' => 'الهندسة، العلوم، الطب، التكنولوجيا', 'fees' => '£40,000 - £55,000', 'ar_fees' => 'من 40,000 إلى 55,000 جنيه إسترليني سنويًا', 'website' => 'https://www.imperial.ac.uk', 'established_year' => 1907],
            ['name' => 'University of Oxford', 'slug' => 'university-of-oxford', 'city' => 'Oxford', 'qs_ranking' => 4, 'the_ranking' => 1, 'shanghai_ranking' => 6, 'famous_for' => 'Law, Medicine, Humanities', 'ar_famous_for' => 'القانون، الطب، العلوم الإنسانية', 'fees' => '£35,000 - £60,000', 'ar_fees' => 'من 35,000 إلى 60,000 جنيه إسترليني سنويًا', 'website' => 'https://www.ox.ac.uk', 'established_year' => 1096],
            ['name' => 'University of Cambridge', 'slug' => 'university-of-cambridge', 'city' => 'Cambridge', 'qs_ranking' => 6, 'the_ranking' => 3, 'shanghai_ranking' => 4, 'famous_for' => 'Sciences, Engineering, Mathematics', 'ar_famous_for' => 'العلوم، الهندسة، الرياضيات', 'fees' => '£27,000 - £67,000', 'ar_fees' => 'من 27,000 إلى 67,000 جنيه إسترليني سنويًا', 'website' => 'https://www.cam.ac.uk', 'established_year' => 1209],
            ['name' => 'University College London', 'slug' => 'university-college-london', 'city' => 'London', 'qs_ranking' => 9, 'the_ranking' => 22, 'shanghai_ranking' => 14, 'famous_for' => 'Medicine, Law, Architecture, Social Sciences', 'ar_famous_for' => 'الطب، القانون، العمارة، العلوم الاجتماعية', 'fees' => '£27,000 - £45,000', 'ar_fees' => 'من 27,000 إلى 45,000 جنيه إسترليني سنويًا', 'website' => 'https://www.ucl.ac.uk', 'established_year' => 1826],
            ['name' => "King's College London", 'slug' => 'kings-college-london', 'city' => 'London', 'qs_ranking' => 31, 'the_ranking' => 38, 'shanghai_ranking' => 61, 'famous_for' => 'Medicine, Law, International Relations', 'ar_famous_for' => 'الطب، القانون، العلاقات الدولية', 'fees' => '£25,000 - £50,000', 'ar_fees' => 'من 25,000 إلى 50,000 جنيه إسترليني سنويًا', 'website' => 'https://www.kcl.ac.uk', 'established_year' => 1829],
            ['name' => 'University of Edinburgh', 'slug' => 'university-of-edinburgh', 'city' => 'Edinburgh', 'qs_ranking' => 34, 'the_ranking' => 29, 'shanghai_ranking' => 37, 'famous_for' => 'Medicine, Law, Sciences, Literature', 'ar_famous_for' => 'الطب، القانون، العلوم، الأدب', 'fees' => '£24,000 - £52,000', 'ar_fees' => 'من 24,000 إلى 52,000 جنيه إسترليني سنويًا', 'website' => 'https://www.ed.ac.uk', 'established_year' => 1582],
            ['name' => 'University of Manchester', 'slug' => 'university-of-manchester', 'city' => 'Manchester', 'qs_ranking' => 35, 'the_ranking' => 56, 'shanghai_ranking' => 46, 'famous_for' => 'Engineering, Business, Computer Science', 'ar_famous_for' => 'الهندسة، إدارة الأعمال، علوم الحاسوب', 'fees' => '£25,000 - £52,000', 'ar_fees' => 'من 25,000 إلى 52,000 جنيه إسترليني سنويًا', 'website' => 'https://www.manchester.ac.uk', 'established_year' => 1824],
            ['name' => 'University of Bristol', 'slug' => 'university-of-bristol', 'city' => 'Bristol', 'qs_ranking' => 51, 'the_ranking' => 80, 'shanghai_ranking' => 98, 'famous_for' => 'Engineering, Law, Economics, Medicine, Computer Science', 'ar_famous_for' => 'الهندسة، القانون، الاقتصاد، الطب، علوم الحاسوب', 'fees' => '£25,000 - £45,000', 'ar_fees' => 'من 25,000 إلى 45,000 جنيه إسترليني سنويًا', 'website' => 'https://www.bristol.ac.uk', 'established_year' => 1909],
            ['name' => 'London School of Economics and Political Science (LSE)', 'slug' => 'london-school-of-economics-and-political-science', 'city' => 'London', 'qs_ranking' => 56, 'the_ranking' => 52, 'shanghai_ranking' => 151, 'famous_for' => 'Economics, Politics, International Relations, Finance, Social Sciences', 'ar_famous_for' => 'الاقتصاد، السياسة، العلاقات الدولية، المالية، العلوم الاجتماعية', 'fees' => '£26,000 - £32,000', 'ar_fees' => 'من 26,000 إلى 32,000 جنيه إسترليني سنويًا', 'website' => 'https://www.lse.ac.uk', 'established_year' => 1895],
            ['name' => 'University of Warwick', 'slug' => 'university-of-warwick', 'city' => 'Coventry', 'qs_ranking' => 74, 'the_ranking' => 122, 'shanghai_ranking' => 101, 'famous_for' => 'Business, Management, Economics, Mathematics, Engineering, Computer Science', 'ar_famous_for' => 'إدارة الأعمال، الإدارة، الاقتصاد، الرياضيات، الهندسة، علوم الحاسوب', 'fees' => '£25,000 - £38,000', 'ar_fees' => 'من 25,000 إلى 38,000 جنيه إسترليني سنويًا', 'website' => 'https://www.warwick.ac.uk', 'established_year' => 1965],
            ['name' => 'University of Birmingham', 'slug' => 'university-of-birmingham', 'city' => 'Birmingham', 'qs_ranking' => 76, 'the_ranking' => 98, 'shanghai_ranking' => 151, 'famous_for' => 'Medicine, Engineering, Business, Law, Education', 'ar_famous_for' => 'الطب، الهندسة، إدارة الأعمال، القانون، التعليم', 'fees' => '£22,000 - £38,000', 'ar_fees' => 'من 22,000 إلى 38,000 جنيه إسترليني سنويًا', 'website' => 'https://www.birmingham.ac.uk', 'established_year' => 1900],
            ['name' => 'University of Glasgow', 'slug' => 'university-of-glasgow', 'city' => 'Glasgow', 'qs_ranking' => 79, 'the_ranking' => 84, 'shanghai_ranking' => 101, 'famous_for' => 'Medicine, Veterinary Medicine, Engineering, Life Sciences', 'ar_famous_for' => 'الطب، الطب البيطري، الهندسة، علوم الحياة', 'fees' => '£23,000 - £45,000', 'ar_fees' => 'من 23,000 إلى 45,000 جنيه إسترليني سنويًا', 'website' => 'https://www.gla.ac.uk', 'established_year' => 1451],
            ['name' => 'University of Leeds', 'slug' => 'university-of-leeds', 'city' => 'Leeds', 'qs_ranking' => 86, 'the_ranking' => 118, 'shanghai_ranking' => 151, 'famous_for' => 'Engineering, Business, Media, Dentistry, Environmental Sciences', 'ar_famous_for' => 'الهندسة، إدارة الأعمال، الإعلام، طب الأسنان، العلوم البيئية', 'fees' => '£22,000 - £40,000', 'ar_fees' => 'من 22,000 إلى 40,000 جنيه إسترليني سنويًا', 'website' => 'https://www.leeds.ac.uk', 'established_year' => 1904],
            ['name' => 'University of Southampton', 'slug' => 'university-of-southampton', 'city' => 'Southampton', 'qs_ranking' => 87, 'the_ranking' => 129, 'shanghai_ranking' => 151, 'famous_for' => 'Engineering, Computer Science, Marine Science, Business', 'ar_famous_for' => 'الهندسة، علوم الحاسوب، العلوم البحرية، إدارة الأعمال', 'fees' => '£23,000 - £42,000', 'ar_fees' => 'من 23,000 إلى 42,000 جنيه إسترليني سنويًا', 'website' => 'https://www.southampton.ac.uk', 'established_year' => 1862],
            ['name' => 'University of Sheffield', 'slug' => 'university-of-sheffield', 'city' => 'Sheffield', 'qs_ranking' => 92, 'the_ranking' => 108, 'shanghai_ranking' => 151, 'famous_for' => 'Engineering, Architecture, Journalism, Computer Science', 'ar_famous_for' => 'الهندسة، العمارة، الصحافة، علوم الحاسوب', 'fees' => '£22,000 - £40,000', 'ar_fees' => 'من 22,000 إلى 40,000 جنيه إسترليني سنويًا', 'website' => 'https://www.sheffield.ac.uk', 'established_year' => 1905],
            ['name' => 'Durham University', 'slug' => 'durham-university', 'city' => 'Durham', 'qs_ranking' => 94, 'the_ranking' => 175, 'shanghai_ranking' => 201, 'famous_for' => 'Law, Theology, Politics, Business, Humanities', 'ar_famous_for' => 'القانون، اللاهوت، السياسة، إدارة الأعمال، العلوم الإنسانية', 'fees' => '£22,000 - £37,000', 'ar_fees' => 'من 22,000 إلى 37,000 جنيه إسترليني سنويًا', 'website' => 'https://www.durham.ac.uk', 'established_year' => 1832],
            ['name' => 'University of Nottingham', 'slug' => 'university-of-nottingham', 'city' => 'Nottingham', 'qs_ranking' => 97, 'the_ranking' => 145, 'shanghai_ranking' => 101, 'famous_for' => 'Pharmacy, Medicine, Engineering, Business, Agriculture', 'ar_famous_for' => 'الصيدلة، الطب، الهندسة، إدارة الأعمال، الزراعة', 'fees' => '£22,000 - £38,000', 'ar_fees' => 'من 22,000 إلى 38,000 جنيه إسترليني سنويًا', 'website' => 'https://www.nottingham.ac.uk', 'established_year' => 1881],
            ['name' => 'Queen Mary University of London', 'slug' => 'queen-mary-university-of-london', 'city' => 'London', 'qs_ranking' => 110, 'the_ranking' => 134, 'shanghai_ranking' => 201, 'famous_for' => 'Law, Medicine, Dentistry, Engineering', 'ar_famous_for' => 'القانون، الطب، طب الأسنان، الهندسة', 'fees' => '£21,000 - £38,000', 'ar_fees' => 'من 21,000 إلى 38,000 جنيه إسترليني سنويًا', 'website' => 'https://www.qmul.ac.uk', 'established_year' => 1887],
            ['name' => 'University of St Andrews', 'slug' => 'university-of-st-andrews', 'city' => 'St Andrews', 'qs_ranking' => 113, 'the_ranking' => 162, 'shanghai_ranking' => 301, 'famous_for' => 'International Relations, Philosophy, Economics, Physics', 'ar_famous_for' => 'العلاقات الدولية، الفلسفة، الاقتصاد، الفيزياء', 'fees' => '£23,000 - £36,000', 'ar_fees' => 'من 23,000 إلى 36,000 جنيه إسترليني سنويًا', 'website' => 'https://www.st-andrews.ac.uk', 'established_year' => 1413],
            ['name' => 'University of Bath', 'slug' => 'university-of-bath', 'city' => 'Bath', 'qs_ranking' => 132, 'the_ranking' => 251, 'shanghai_ranking' => 401, 'famous_for' => 'Engineering, Architecture, Management, Computer Science', 'ar_famous_for' => 'الهندسة، العمارة، الإدارة، علوم الحاسوب', 'fees' => '£23,000 - £39,000', 'ar_fees' => 'من 23,000 إلى 39,000 جنيه إسترليني سنويًا', 'website' => 'https://www.bath.ac.uk', 'established_year' => 1966],
            ['name' => 'Newcastle University', 'slug' => 'newcastle-university', 'city' => 'Newcastle upon Tyne', 'qs_ranking' => 137, 'the_ranking' => 144, 'shanghai_ranking' => 201, 'famous_for' => 'Medicine, Architecture, Engineering, Business', 'ar_famous_for' => 'الطب، العمارة، الهندسة، إدارة الأعمال', 'fees' => '£21,000 - £37,000', 'ar_fees' => 'من 21,000 إلى 37,000 جنيه إسترليني سنويًا', 'website' => 'https://www.ncl.ac.uk', 'established_year' => 1834],
        ];

        foreach ($rows as $row) {
            $cityId = $cityIds[$row['city']] ?? null;
            if (! $cityId) {
                continue;
            }

            DB::table('universities')->updateOrInsert(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'ar_name' => null,
                    'logo' => null,
                    'cover_image' => null,
                    'country_id' => $ukId,
                    'city_id' => $cityId,
                    'type' => 'public',
                    'established_year' => $row['established_year'],
                    'website' => $row['website'],
                    'qs_ranking' => $row['qs_ranking'],
                    'the_ranking' => $row['the_ranking'],
                    'shanghai_ranking' => $row['shanghai_ranking'],
                    'famous_for' => $row['famous_for'],
                    'ar_famous_for' => $row['ar_famous_for'],
                    'fees' => $row['fees'],
                    'ar_fees' => $row['ar_fees'],
                    'is_featured' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }
    }
}
