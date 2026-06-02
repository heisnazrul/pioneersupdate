<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class LanguageSchoolPickupSeeder extends Seeder
{
    /** @var array<string, int> */
    private array $branchMap = [];

    public function run(): void
    {
        $this->branchMap = $this->buildBranchMap();

        $path = database_path('seeders/data/language_school_pickups.json');
        $rows = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $seeded = 0;

        foreach ($rows as $row) {
            $branchId = $this->resolveBranchId($row['school_en'], $row['city'], $row['country']);

            $payload = [
                'pickup_location' => $row['pickup_location'],
                'pickup_location_ar' => null,
                'fee' => $row['fee'] ?? 0,
                'updated_at' => now(),
            ];

            $match = DB::table('language_school_pickups')
                ->where('branch_id', $branchId)
                ->where('pickup_location', $row['pickup_location']);

            if ($match->exists()) {
                $match->update($payload);
            } else {
                $payload['branch_id'] = $branchId;
                $payload['created_at'] = now();
                DB::table('language_school_pickups')->insert($payload);
            }

            $seeded++;

            if ($seeded % 50 === 0) {
                gc_collect_cycles();
            }
        }

        unset($rows);
        gc_collect_cycles();

        $this->command?->info("Seeded {$seeded} language school pickups.");
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
            'IR' => 'IE',
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
}
