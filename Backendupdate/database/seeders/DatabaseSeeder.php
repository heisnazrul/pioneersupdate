<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FreeshUserSeeder::class,
            BlogCategorySeeder::class,
            BlogSeeder::class,
            BathroomTypeSeeder::class,
            BedroomTypeSeeder::class,
            MealPlanSeeder::class,
            LevelSeeder::class,
            SubjectAreaSeeder::class,
            IntakeTermSeeder::class,
            FreshFaqSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            UniversitySeeder::class,
            UniversityCourseCatalogSeeder::class,
            UniversityCourseLevelSeeder::class,
            UniversityCourseSeeder::class,
            DestinationSeeder::class,
        ]);
    }
}
