<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LanguageSchoolBranchSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 6,
                'school_id' => 5,
                'description' => 'The institute is based in London...',
                'ar_description' => 'يقع المعهد في مدينة لندن...',
                'city_id' => 1,
                'branch_images' => '["branch_images\/zXbauucJHKBgUYQTNKeUWEdXHclcB759F6OOHSTP.jpg"]',
                'video' => null,
                'created_at' => '2025-03-25 21:55:13',
                'updated_at' => '2025-03-25 21:55:13',
            ],
            [
                'id' => 7,
                'school_id' => 5,
                'description' => 'The institute is based in London...',
                'ar_description' => 'يقع المعهد في مدينة لندن...',
                'city_id' => 19,
                'branch_images' => '["branch_images\/saZliSlic8lfIQOFdCAM7hAipVZZ7dXQnOWqE7t3.jpg"]',
                'video' => null,
                'created_at' => '2025-03-25 22:03:21',
                'updated_at' => '2025-03-25 22:03:21',
            ],
            [
                'id' => 8,
                'school_id' => 5,
                'description' => 'The institute is based in London...',
                'ar_description' => 'يقع المعهد في مدينة لندن...',
                'city_id' => 20,
                'branch_images' => '["branch_images\/xtQEm27paxGTQUIfbwWvvnNUhQrWbzWXcXfhKjyB.jpg"]',
                'video' => null,
                'created_at' => '2025-03-25 22:04:58',
                'updated_at' => '2025-03-25 22:04:58',
            ],
            [
                'id' => 9,
                'school_id' => 7,
                'description' => 'Bath Academy of English...',
                'ar_description' => 'أكاديمية بات الإنجليزية...',
                'city_id' => 22,
                'branch_images' => '["branch_images\/AzCHWDovmV2Q7u9fumat3lhEaA5EDPUwn8I9N3cr.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:10:16',
                'updated_at' => '2025-04-01 20:10:16',
            ],
            [
                'id' => 10,
                'school_id' => 8,
                'description' => 'Concorde International...',
                'ar_description' => 'كونكورد إنترناشونال...',
                'city_id' => 34,
                'branch_images' => '["branch_images\/9OjxRxqrgXLyXDAnqIGJMQ4eaQ5WemF0aoLcy7bB.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:12:11',
                'updated_at' => '2025-04-01 20:12:11',
            ],
            [
                'id' => 12,
                'school_id' => 9,
                'description' => 'The Islington Centre for English...',
                'ar_description' => 'يتموقع معهد The Islington Centre...',
                'city_id' => 1,
                'branch_images' => '["branch_images\/CBidsV0PH3X2qKPhxQz9FJeRDVHMOyTmf9lMcxV3.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:15:17',
                'updated_at' => '2025-04-01 20:15:17',
            ],
            [
                'id' => 13,
                'school_id' => 10,
                'description' => 'The London School of English...',
                'ar_description' => 'معهد The London School of English...',
                'city_id' => 1,
                'branch_images' => '["branch_images\/qcWZAKG61C0LMNPuA8BDUDctL1UnEEmIzR5FBUb6.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:17:04',
                'updated_at' => '2025-04-01 20:17:04',
            ],
            [
                'id' => 14,
                'school_id' => 11,
                'description' => 'BEET English Language Center...',
                'ar_description' => 'معهد بيت إنجلش سنتر...',
                'city_id' => 24,
                'branch_images' => '["branch_images\/5zdbZ33NfEsO08uSMbjr6ny5ATvSDOH1bHYlpBnc.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:18:42',
                'updated_at' => '2025-04-01 20:18:42',
            ],
            [
                'id' => 15,
                'school_id' => 12,
                'description' => 'Berlitz Institute...',
                'ar_description' => 'معهد بيرلتز...',
                'city_id' => 2,
                'branch_images' => '["branch_images\/Qil6LjbxJrZyqjXfURX8NHAQuggniHzscVChC13p.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:19:22',
                'updated_at' => '2025-04-01 20:19:22',
            ],
            [
                'id' => 16,
                'school_id' => 13,
                'description' => 'Britannia English Academy...',
                'ar_description' => 'أكاديمية بريتانيا الإنجليزية...',
                'city_id' => 2,
                'branch_images' => '["branch_images\/BOzeLRnwplHO1ToNbdA3IqtIAMRKeMt9uIX1qxz0.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:20:11',
                'updated_at' => '2025-04-01 20:20:11',
            ],
            [
                'id' => 17,
                'school_id' => 14,
                'description' => 'Oxford International Study Centre...',
                'ar_description' => 'مركز أكسفورد الدولي للدراسات...',
                'city_id' => 21,
                'branch_images' => '["branch_images\/IuFQLQuFdJYl5KMnNr8AwyF2ajUEmJ6iUpegNKL5.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:20:47',
                'updated_at' => '2025-04-01 20:20:47',
            ],
            [
                'id' => 18,
                'school_id' => 15,
                'description' => 'Preston Academy of English...',
                'ar_description' => 'أكاديمية بريستون...',
                'city_id' => 54,
                'branch_images' => '["branch_images\/rbkowcEL6dsVVOt0XKtvcRhM9EL06TUlERhKH6oc.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:22:19',
                'updated_at' => '2025-04-01 20:22:19',
            ],
            [
                'id' => 19,
                'school_id' => 16,
                'description' => 'Select English...',
                'ar_description' => 'تُعدّ Select English...',
                'city_id' => 20,
                'branch_images' => '["branch_images\/9uVCx1MlDYKSWdeqCxh2t3uq96HksK2x9J9ETsQH.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:23:04',
                'updated_at' => '2025-04-01 20:23:04',
            ],
            [
                'id' => 20,
                'school_id' => 17,
                'description' => 'UK College of English...',
                'ar_description' => 'هو معهد لغات مستقل...',
                'city_id' => 1,
                'branch_images' => '["branch_images\/UjFJTA0YaaJLUplYWfLFNYRFS9eEZsIayjxu8xjv.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:23:46',
                'updated_at' => '2025-04-01 20:23:46',
            ],
            [
                'id' => 21,
                'school_id' => 18,
                'description' => 'Westbourne Academy...',
                'ar_description' => 'أكاديمية ويستبورن...',
                'city_id' => 24,
                'branch_images' => '["branch_images\/S7tHUf6BkAqdpFNUgJPac9TEZzBoEHMIqudzZZa9.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:24:33',
                'updated_at' => '2025-04-01 20:24:33',
            ],
            [
                'id' => 22,
                'school_id' => 20,
                'description' => 'Bright School of English...',
                'ar_description' => 'مدرسة برايت...',
                'city_id' => 24,
                'branch_images' => '["branch_images\/rj2KUaNpg6GxN0DwiWwJ3BzV7hfBlFs6zsQXcw6j.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:25:13',
                'updated_at' => '2025-04-01 20:25:13',
            ],
            [
                'id' => 23,
                'school_id' => 21,
                'description' => 'Southbourne School of English...',
                'ar_description' => 'تأسست مدرسة ساوثبورن...',
                'city_id' => 24,
                'branch_images' => '["branch_images\/ZnsacDUD6tChnp7FxiTn7vYXUkB4DZlrhdmnBpzt.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:26:06',
                'updated_at' => '2025-04-01 20:26:06',
            ],
            [
                'id' => 24,
                'school_id' => 22,
                'description' => 'Twin English Centres...',
                'ar_description' => 'تأسست مراكز توين...',
                'city_id' => 1,
                'branch_images' => '["branch_images\/RuZBn3eGEDfBLP9M1UrBsz7VvsuxVHnITZ0B3DJv.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:26:45',
                'updated_at' => '2025-04-01 20:26:45',
            ],
            [
                'id' => 25,
                'school_id' => 23,
                'description' => 'Burlington School...',
                'ar_description' => 'تأسست مدرسة بيرلينغتون...',
                'city_id' => 1,
                'branch_images' => '["branch_images\/yBfQTzwDF0BlcenPvRz7amxEXcHqbWJfWAuWg5IT.jpg"]',
                'video' => null,
                'created_at' => '2025-04-01 20:29:13',
                'updated_at' => '2025-04-01 20:29:13',
            ],
        ];

        foreach ($rows as $row) {
            $schoolName = DB::table('language_schools')->where('id', $row['school_id'])->value('name');
            $cityName = DB::table('cities')->where('id', $row['city_id'])->value('name');

            $slug = $schoolName && $cityName
                ? Str::slug($schoolName . '-' . $cityName)
                : 'school-' . $row['school_id'] . '-city-' . $row['city_id'];

            $galleryUrls = [];
            if (! empty($row['branch_images'])) {
                $decoded = json_decode($row['branch_images'], true);
                if (is_array($decoded)) {
                    $galleryUrls = $decoded;
                }
            }

            DB::table('language_school_branches')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'language_school_id' => $row['school_id'],
                    'city_id' => $row['city_id'],
                    'slug' => $slug,
                    'description' => $row['description'],
                    'ar_description' => $row['ar_description'],
                    'gallery_urls' => json_encode($galleryUrls),
                    'video_url' => $row['video'],
                    'created_at' => Carbon::parse($row['created_at']),
                    'updated_at' => Carbon::parse($row['updated_at']),
                ]
            );
        }
    }
}
