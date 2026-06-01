<?php

namespace Database\Seeders;

use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class LanguageSchoolBranchSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(
            file_get_contents(database_path('seeders/data/language_school_branches.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $seeded = 0;

        foreach ($rows as $row) {
            $school = LanguageSchool::query()
                ->where('slug', Str::slug($row['school_en']))
                ->first();

            if (! $school) {
                throw new RuntimeException("Language school not found: {$row['school_en']}. Run LanguageSchoolSeeder first.");
            }

            $cityId = $this->resolveCityId($row['city'], $row['country']);
            $slug = Str::slug($school->name_en . '-' . $row['city']);

            LanguageSchoolBranch::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'city_id' => $cityId,
                    'slug' => $slug,
                ],
                [
                    'about_en' => $row['about_en'] ?: null,
                    'about_ar' => $row['about_ar'] ?: null,
                    'new_year_close_from' => $this->parseSheetDate($row['close_from'] ?? null),
                    'new_year_close_to' => $this->parseSheetDate($row['close_to'] ?? null),
                    'branch_images' => null,
                    'is_active' => 'yes',
                ]
            );

            $seeded++;
        }

        $this->command?->info("Seeded {$seeded} language school branches.");
    }

    private function resolveCityId(string $cityName, string $countryCode): int
    {
        $countryCode = match (strtoupper(trim($countryCode))) {
            'UK' => 'GB',
            default => strtoupper(trim($countryCode)),
        };

        $cityId = DB::table('cities')
            ->join('countries', 'countries.id', '=', 'cities.country_id')
            ->where('countries.country_code', $countryCode)
            ->where('cities.name', trim($cityName))
            ->value('cities.id');

        if (! $cityId) {
            throw new RuntimeException("City not found: {$cityName} ({$countryCode}). Run CountrySeeder and CitySeeder first.");
        }

        return (int) $cityId;
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
