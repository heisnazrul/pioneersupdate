<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversityCourseSeeder extends Seeder
{
    public function run(): void
    {
        $countryCurrencies = DB::table('countries')->pluck('currency_code', 'id');
        $universities = DB::table('universities')->where('is_active', true)->get(['id', 'country_id']);
        $catalogs = DB::table('university_course_catalogs')->where('is_active', true)->get(['id']);
        $levels = DB::table('levels')->where('is_active', true)->orderBy('sort_order')->get(['id', 'key']);
        $intakeTerms = DB::table('intake_terms')->where('is_active', true)->orderBy('sort_order')->get(['id']);
        $now = now();

        $durationMap = [
            'foundation' => [1, 'year'],
            'international-foundation' => [1, 'year'],
            'bachelor' => [3, 'years'],
            'bachelor-top-up' => [1, 'year'],
            'integrated-master' => [4, 'years'],
            'pre-masters' => [1, 'year'],
            'graduate-diploma' => [1, 'year'],
            'postgraduate-certificate' => [1, 'year'],
            'postgraduate-diploma' => [1, 'year'],
            'masters' => [1, 'year'],
            'mba' => [1, 'year'],
            'mres' => [1, 'year'],
            'phd-doctorate' => [3, 'years'],
            'professional-doctorate' => [3, 'years'],
            'short-course' => [6, 'months'],
            'distance-learning' => [2, 'years'],
        ];

        foreach ($universities as $university) {
            foreach ($catalogs as $catalog) {
                foreach ($levels as $level) {
                    [$durationValue, $durationUnit] = $durationMap[$level->key] ?? [1, 'year'];
                    $currency = $countryCurrencies[$university->country_id] ?? 'USD';

                    DB::table('university_courses')->updateOrInsert(
                        [
                            'university_id' => $university->id,
                            'course_catalog_id' => $catalog->id,
                            'level_id' => $level->id,
                        ],
                        [
                            'duration_value' => $durationValue,
                            'duration_unit' => $durationUnit,
                            'first_year_fee' => null,
                            'currency' => $currency,
                            'overview' => null,
                            'ar_overview' => null,
                            'awarding_body' => null,
                            'ar_awarding_body' => null,
                            'degree_requirement' => null,
                            'language_requirement' => null,
                            'is_active' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            }
        }

        $courses = DB::table('university_courses')->get(['id']);
        foreach ($courses as $course) {
            foreach ($intakeTerms as $term) {
                DB::table('university_course_intakes')->updateOrInsert(
                    [
                        'university_course_id' => $course->id,
                        'intake_term_id' => $term->id,
                    ],
                    [
                        'deadline_date' => null,
                        'start_date' => null,
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }
    }
}
