<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class LanguageSchoolCoursePromotionSeeder extends Seeder
{
    /** @var array<string, int> */
    private array $branchMap = [];

    /** @var array<string, int> */
    private array $courseMap = [];

    public function run(): void
    {
        $this->branchMap = $this->buildBranchMap();
        $this->courseMap = $this->buildCourseMap();

        $rows = json_decode(
            file_get_contents(database_path('seeders/data/language_school_courses.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $seeded = 0;

        foreach ($rows as $row) {
            if (empty($row['promotion'])) {
                continue;
            }

            $courseId = $this->findCourseId($row);

            if (! $courseId) {
                throw new RuntimeException(sprintf(
                    'Course not found for promotion: %s / %s / %s',
                    $row['school_en'],
                    $row['city'],
                    $row['course_name_from_school'],
                ));
            }

            $promo = $row['promotion'];

            $values = [
                'promo_from' => $this->parseSheetDate($promo['promo_from'] ?? null),
                'promo_to' => $this->parseSheetDate($promo['promo_to'] ?? null),
                'is_active' => 'yes',
                'updated_at' => now(),
            ];

            $exists = DB::table('language_school_course_promotions')
                ->where('course_id', $courseId)
                ->where('promotion_percentage', $promo['promotion_percentage'])
                ->exists();

            if ($exists) {
                DB::table('language_school_course_promotions')
                    ->where('course_id', $courseId)
                    ->where('promotion_percentage', $promo['promotion_percentage'])
                    ->update($values);
            } else {
                DB::table('language_school_course_promotions')->insert(array_merge($values, [
                    'course_id' => $courseId,
                    'promotion_percentage' => $promo['promotion_percentage'],
                    'created_at' => now(),
                ]));
            }

            $seeded++;
        }

        $this->command?->info("Seeded {$seeded} language school course promotions.");
    }

    /**
     * @return array<string, int>
     */
    private function buildBranchMap(): array
    {
        $map = [];

        $rows = DB::table('language_school_branches as b')
            ->join('language_schools as s', 's.id', '=', 'b.school_id')
            ->join('cities as c', 'c.id', '=', 'b.city_id')
            ->join('countries as co', 'co.id', '=', 'c.country_id')
            ->select('b.id as branch_id', 's.slug as school_slug', 'c.name as city_name', 'co.country_code')
            ->get();

        foreach ($rows as $row) {
            $key = $this->branchKey($row->school_slug, $row->city_name, $row->country_code);
            $map[$key] = (int) $row->branch_id;
        }

        return $map;
    }

    /**
     * @return array<string, int>
     */
    private function buildCourseMap(): array
    {
        $map = [];

        $rows = DB::table('language_school_courses')->select('id', 'branch_id', 'slug')->get();

        foreach ($rows as $row) {
            $map[$row->branch_id . '|' . $row->slug] = (int) $row->id;
        }

        return $map;
    }

    private function findCourseId(array $row): ?int
    {
        $branchId = $this->resolveBranchId($row['school_en'], $row['city'], $row['country']);
        $slug = $this->courseSlug($row);

        return $this->courseMap[$branchId . '|' . $slug] ?? null;
    }

    private function resolveBranchId(string $schoolName, string $cityName, string $countryCode): int
    {
        $schoolSlug = Str::slug($this->normalizeSchoolName($schoolName));
        $cityName = trim($cityName);
        $cityName = $cityName === 'Pairs' ? 'Paris' : $cityName;
        $countryCode = match (strtoupper(trim($countryCode))) {
            'UK' => 'GB',
            default => strtoupper(trim($countryCode)),
        };

        $key = $this->branchKey($schoolSlug, $cityName, $countryCode);
        $branchId = $this->branchMap[$key] ?? null;

        if (! $branchId) {
            throw new RuntimeException("Branch not found: {$schoolName} / {$cityName}.");
        }

        return $branchId;
    }

    private function branchKey(string $schoolSlug, string $cityName, string $countryCode): string
    {
        return strtolower($schoolSlug . '|' . trim($cityName) . '|' . strtoupper($countryCode));
    }

    private function normalizeSchoolName(string $name): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($name));

        return match (strtolower($normalized)) {
            'bright school of english' => 'Bright School Of English',
            default => $normalized,
        };
    }

    private function courseSlug(array $row): string
    {
        $parts = [
            $row['course_type'],
            $row['course_name_from_school'],
            $row['hours_per_week'] ?? '',
            $row['lessons_per_week'] ?? '',
            $row['min_age'] ?? '',
        ];

        return Str::slug(implode('-', array_filter($parts, fn ($part) => $part !== '' && $part !== null)));
    }

    private function parseSheetDate(?string $value): ?string
    {
        $value = trim((string) $value, " \t\n\r\0\x0B\"");

        if ($value === '') {
            return null;
        }

        return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
}
