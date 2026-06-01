<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class LanguageSchoolCourseSeeder extends Seeder
{
    /** @var array<string, int> */
    private array $branchMap = [];

    /** @var array<string, int> */
    private array $categoryMap = [];

    public function run(): void
    {
        $this->branchMap = $this->buildBranchMap();
        $this->categoryMap = DB::table('language_course_categories')->pluck('id', 'slug')->all();

        $path = database_path('seeders/data/language_school_courses.json');
        $rows = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $seeded = 0;

        foreach ($rows as $row) {
            $branchId = $this->resolveBranchId($row['school_en'], $row['city'], $row['country']);
            $categorySlug = Str::slug($row['course_type']);
            $categoryId = $this->categoryMap[$categorySlug] ?? null;

            if (! $categoryId) {
                throw new RuntimeException("Course category not found: {$row['course_type']}. Run LanguageCourseCategorySeeder first.");
            }

            $slug = $this->courseSlug($row);
            $payload = [
                'branch_id' => $branchId,
                'course_category_id' => $categoryId,
                'course_name_from_school' => $row['course_name_from_school'],
                'course_name_from_school_ar' => null,
                'slug' => $slug,
                'hours_per_week' => $row['hours_per_week'],
                'lessons_per_week' => $row['lessons_per_week'],
                'min_level' => $row['min_level'],
                'min_age' => $row['min_age'],
                'material_books_fee' => $row['material_books_fee'] ?? 0,
                'registration_admin_fee' => $row['registration_admin_fee'] ?? 0,
                'mandatory_additional_fee_name' => $row['mandatory_additional_fee_name'] ?: null,
                'mandatory_additional_fee' => $row['mandatory_additional_fee'] ?? 0,
                'is_active' => 'yes',
                'updated_at' => now(),
            ];

            foreach (range(1, 7) as $slot) {
                $payload["week_category_{$slot}"] = null;
                $payload["weekly_fee_{$slot}"] = null;
            }

            foreach ($row['tiers'] as $index => $tier) {
                $slot = $index + 1;

                if ($slot > 7) {
                    break;
                }

                $payload["week_category_{$slot}"] = $tier['week_category'];
                $payload["weekly_fee_{$slot}"] = $tier['weekly_fee'];
            }

            $exists = DB::table('language_school_courses')
                ->where('branch_id', $branchId)
                ->where('slug', $slug)
                ->exists();

            if ($exists) {
                DB::table('language_school_courses')
                    ->where('branch_id', $branchId)
                    ->where('slug', $slug)
                    ->update($payload);
            } else {
                $payload['created_at'] = now();
                DB::table('language_school_courses')->insert($payload);
            }

            $seeded++;

            if ($seeded % 50 === 0) {
                gc_collect_cycles();
            }
        }

        unset($rows);
        gc_collect_cycles();

        $this->command?->info("Seeded {$seeded} language school courses.");
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
            throw new RuntimeException("Branch not found: {$schoolName} / {$cityName}. Run LanguageSchoolBranchSeeder first.");
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
}
