<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CourseEnglishWishlistSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'hero' => [
                'title' => 'Your Wishlist',
                'subtitle' => 'View and manage your saved courses and institutes.',
            ],
            'card' => [
                'type_suffix' => '/ week',
                'view_details_text' => 'View Details',
            ],
            'empty_state' => [
                'title' => 'Your wishlist is empty',
                'subtitle' => 'Start exploring to find your perfect course.',
                'cta_text' => 'Explore Courses',
                'cta_url' => '/',
            ],
        ];

        $ar = [
            'hero' => [
                'title' => 'قائمة رغباتك',
                'subtitle' => 'اعرض وأدر الدورات والمعاهد التي حفظتها.',
            ],
            'card' => [
                'type_suffix' => '/ أسبوع',
                'view_details_text' => 'عرض التفاصيل',
            ],
            'empty_state' => [
                'title' => 'قائمة رغباتك فارغة',
                'subtitle' => 'ابدأ الاستكشاف للعثور على الدورة المناسبة لك.',
                'cta_text' => 'استكشف الدورات',
                'cta_url' => '/',
            ],
        ];

        CmsPage::updateOrCreate(
            ['app' => 'courseenglish', 'slug' => 'wishlist'],
            [
                'title' => 'Wishlist',
                'ar_title' => 'قائمة الرغبات',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ar_content' => json_encode($ar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'meta_title' => 'Wishlist',
                'meta_description' => 'View and manage your saved courses and institutes.',
                'is_active' => true,
                'display_order' => 11,
            ]
        );
    }
}
