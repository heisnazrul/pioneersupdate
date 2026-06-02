<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class LanguageSchoolAccommodationSeeder extends Seeder
{
    /** @var array<string, int> */
    private array $branchMap = [];

    /** @var array<string, int> */
    private array $accommodationTypeMap = [];

    /** @var array<string, int> */
    private array $bedroomTypeMap = [];

    /** @var array<string, int> */
    private array $bathroomTypeMap = [];

    /** @var array<string, int> */
    private array $mealPlanMap = [];

    public function run(): void
    {
        $this->branchMap = $this->buildBranchMap();
        $this->accommodationTypeMap = DB::table('accommodation_types')->pluck('id', 'name_en')->all();
        $this->bedroomTypeMap = DB::table('bedroom_types')->pluck('id', 'name_en')->all();
        $this->bathroomTypeMap = DB::table('bathroom_types')->pluck('id', 'name_en')->all();
        $this->mealPlanMap = DB::table('meal_plans')->pluck('id', 'name_en')->all();

        $path = database_path('seeders/data/language_school_accommodations.json');
        $rows = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $seeded = 0;

        foreach ($rows as $row) {
            $branchId = $this->resolveBranchId($row['school_en'], $row['city'], $row['country']);
            $accommodationTypeId = $this->accommodationTypeMap[$row['accommodation_type']] ?? null;

            if (! $accommodationTypeId) {
                throw new RuntimeException("Accommodation type not found: {$row['accommodation_type']}. Run AccommodationTypeSeeder first.");
            }

            $bedroomTypeId = $this->lookupId($this->bedroomTypeMap, $row['bedroom_type'] ?? null, 'bedroom');
            $bathroomTypeId = $this->lookupId($this->bathroomTypeMap, $row['bathroom_type'] ?? null, 'bathroom');
            $mealPlanId = $this->lookupId($this->mealPlanMap, $row['meal_plan'] ?? null, 'meal plan');

            $payload = [
                'branch_id' => $branchId,
                'accommodation_type_id' => $accommodationTypeId,
                'name' => $row['name'],
                'name_ar' => null,
                'bedroom_type_id' => $bedroomTypeId,
                'bathroom_type_id' => $bathroomTypeId,
                'meal_plan_id' => $mealPlanId,
                'weekly_fee' => $row['weekly_fee'] ?? 0,
                'admin_fee' => $row['admin_fee'] ?? 0,
                'security_deposit' => $row['security_deposit'] ?? 0,
                'min_age' => $row['min_age'],
                'under_18_supplement_fee' => $row['under_18_supplement_fee'] ?? 0,
                'summer_supplement_fee' => $row['summer_supplement_fee'] ?? 0,
                'summer_start_date' => $row['summer_start_date'],
                'summer_end_date' => $row['summer_end_date'],
                'winter_supplement_fee' => $row['winter_supplement_fee'] ?? 0,
                'winter_start_date' => $row['winter_start_date'],
                'winter_end_date' => $row['winter_end_date'],
                'other_supplement_name' => $row['other_supplement_name'] ?: null,
                'other_supplement_fee' => $row['other_supplement_fee'] ?? 0,
                'other_start_date' => $row['other_start_date'],
                'other_end_date' => $row['other_end_date'],
                'is_active' => 'yes',
                'updated_at' => now(),
            ];

            $match = DB::table('language_school_accommodations')
                ->where('branch_id', $branchId)
                ->where('accommodation_type_id', $accommodationTypeId)
                ->where('name', $row['name'])
                ->where('weekly_fee', $payload['weekly_fee']);

            $this->applyNullableForeignMatch($match, 'bedroom_type_id', $bedroomTypeId);
            $this->applyNullableForeignMatch($match, 'bathroom_type_id', $bathroomTypeId);
            $this->applyNullableForeignMatch($match, 'meal_plan_id', $mealPlanId);

            if ($match->exists()) {
                $match->update($payload);
            } else {
                $payload['created_at'] = now();
                DB::table('language_school_accommodations')->insert($payload);
            }

            $seeded++;

            if ($seeded % 50 === 0) {
                gc_collect_cycles();
            }
        }

        unset($rows);
        gc_collect_cycles();

        $this->command?->info("Seeded {$seeded} language school accommodations.");
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
            throw new RuntimeException("Branch not found: {$schoolName} / {$cityName} ({$countryCode}). Run LanguageSchoolBranchSeeder first.");
        }

        return $branchId;
    }

    private function branchKey(string $schoolSlug, string $cityName, string $countryCode): string
    {
        return strtolower($schoolSlug.'|'.trim($cityName).'|'.strtoupper($countryCode));
    }

    private function normalizeSchoolName(string $name): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($name));

        return match (strtolower($normalized)) {
            'bright school of english' => 'Bright School Of English',
            default => $normalized,
        };
    }

    /**
     * @param  array<string, int>  $map
     */
    private function lookupId(array $map, ?string $nameEn, string $label): ?int
    {
        if ($nameEn === null || $nameEn === '') {
            return null;
        }

        $id = $map[$nameEn] ?? null;

        if (! $id) {
            throw new RuntimeException("{$label} not found: {$nameEn}. Run lookup seeders first.");
        }

        return (int) $id;
    }

    private function applyNullableForeignMatch($query, string $column, ?int $value): void
    {
        if ($value === null) {
            $query->whereNull($column);
        } else {
            $query->where($column, $value);
        }
    }
}
