<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            UniversitySeeder::class,
            LevelSeeder::class,
            SubjectAreaSeeder::class,
            IntakeTermSeeder::class,
            UniversityCourseCatalogSeeder::class,
            UniversityCourseLevelSeeder::class,
            UniversityCourseSeeder::class,
            DestinationSeeder::class,
            BlogSeeder::class,
            BrandingSeeder::class,
            UniversityBrandingSeeder::class,
            CourseEnglishHomeSeeder::class,
            CourseEnglishOffersSeeder::class,
            CourseEnglishAboutSeeder::class,
            CourseEnglishLanguageInstitutesSeeder::class,
            CourseEnglishLanguageInstituteDetailSeeder::class,
            CourseEnglishArticlesSeeder::class,
            CourseEnglishContactSeeder::class,
            CourseEnglishTravelSeeder::class,
            CourseEnglishUniversityAdmissionsSeeder::class,
            CourseEnglishCompareSeeder::class,
            CourseEnglishWishlistSeeder::class,
            CourseEnglishOnlineCoursesSeeder::class,
            CourseEnglishSummerProgramsSeeder::class,
            CourseEnglishTrainingCoursesSeeder::class,
            UniversityCmsSeeder::class,
        ]);
    }
}
