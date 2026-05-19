<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSchoolPickupSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['id' => 12, 'name' => 'Heathrow Airport', 'ar_name' => 'مطار هيثرو', 'school_branch_id' => 6, 'fee' => 120.00, 'created_at' => '2025-03-26 02:54:10', 'updated_at' => '2025-03-26 02:54:10'],
            ['id' => 13, 'name' => 'Gatwick Airport', 'ar_name' => 'مطار جاتويك', 'school_branch_id' => 6, 'fee' => 150.00, 'created_at' => '2025-03-26 02:54:44', 'updated_at' => '2025-03-26 02:54:44'],
            ['id' => 14, 'name' => 'London City Airport', 'ar_name' => 'مطار مدينة لندن', 'school_branch_id' => 6, 'fee' => 120.00, 'created_at' => '2025-03-26 02:55:30', 'updated_at' => '2025-03-26 02:55:30'],
            ['id' => 15, 'name' => 'Stansted Airport', 'ar_name' => 'مطار ستانستيد', 'school_branch_id' => 6, 'fee' => 150.00, 'created_at' => '2025-03-26 02:56:02', 'updated_at' => '2025-03-26 02:56:02'],
            ['id' => 16, 'name' => 'Luton Airport', 'ar_name' => 'مطار لوتون', 'school_branch_id' => 6, 'fee' => 150.00, 'created_at' => '2025-03-26 02:56:23', 'updated_at' => '2025-03-26 02:56:23'],
            ['id' => 17, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 7, 'fee' => 170.00, 'created_at' => '2025-04-03 02:36:47', 'updated_at' => '2025-04-03 02:36:47'],
            ['id' => 18, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 7, 'fee' => 95.00, 'created_at' => '2025-04-03 02:37:26', 'updated_at' => '2025-04-03 02:37:26'],
            ['id' => 19, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 7, 'fee' => 240.00, 'created_at' => '2025-04-03 02:37:41', 'updated_at' => '2025-04-03 02:37:41'],
            ['id' => 20, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 7, 'fee' => 240.00, 'created_at' => '2025-04-03 02:38:16', 'updated_at' => '2025-04-03 02:38:16'],
            ['id' => 21, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 8, 'fee' => 200.00, 'created_at' => '2025-04-03 02:38:56', 'updated_at' => '2025-04-03 02:38:56'],
            ['id' => 22, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 8, 'fee' => 225.00, 'created_at' => '2025-04-03 02:39:17', 'updated_at' => '2025-04-03 02:39:17'],
            ['id' => 23, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 8, 'fee' => 125.00, 'created_at' => '2025-04-03 02:39:32', 'updated_at' => '2025-04-03 02:39:32'],
            ['id' => 24, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 8, 'fee' => 140.00, 'created_at' => '2025-04-03 02:39:53', 'updated_at' => '2025-04-03 02:39:53'],
            ['id' => 25, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 22, 'fee' => 260.00, 'created_at' => '2025-04-03 02:40:29', 'updated_at' => '2025-04-03 02:40:29'],
            ['id' => 26, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 22, 'fee' => 280.00, 'created_at' => '2025-04-03 02:40:42', 'updated_at' => '2025-04-03 02:40:42'],
            ['id' => 27, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 22, 'fee' => 290.00, 'created_at' => '2025-04-03 02:40:52', 'updated_at' => '2025-04-03 02:40:52'],
            ['id' => 28, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 22, 'fee' => 310.00, 'created_at' => '2025-04-03 02:41:29', 'updated_at' => '2025-04-03 02:41:29'],
            ['id' => 29, 'name' => 'Southampton Airport', 'ar_name' => 'مطار ساوثهامبتون', 'school_branch_id' => 22, 'fee' => 70.00, 'created_at' => '2025-04-03 02:41:42', 'updated_at' => '2025-04-03 02:41:42'],
            ['id' => 30, 'name' => 'Bournemouth Airport', 'ar_name' => 'مطار بورنموث', 'school_branch_id' => 22, 'fee' => 40.00, 'created_at' => '2025-04-03 02:41:54', 'updated_at' => '2025-04-03 02:41:54'],
            ['id' => 31, 'name' => 'Poole Ferry Terminal', 'ar_name' => 'ميناء العبّارات في بول', 'school_branch_id' => 22, 'fee' => 35.00, 'created_at' => '2025-04-03 02:42:12', 'updated_at' => '2025-04-03 02:42:12'],
            ['id' => 32, 'name' => 'Central London Airport', 'ar_name' => 'مطار لندن المركزي', 'school_branch_id' => 22, 'fee' => 280.00, 'created_at' => '2025-04-03 02:42:31', 'updated_at' => '2025-04-03 02:42:31'],
            ['id' => 33, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 13, 'fee' => 140.00, 'created_at' => '2025-04-03 02:43:01', 'updated_at' => '2025-04-03 02:43:01'],
            ['id' => 34, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 13, 'fee' => 195.00, 'created_at' => '2025-04-03 02:43:45', 'updated_at' => '2025-04-03 02:43:45'],
            ['id' => 35, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 13, 'fee' => 215.00, 'created_at' => '2025-04-03 02:44:01', 'updated_at' => '2025-04-03 02:44:01'],
            ['id' => 36, 'name' => 'Bournemouth Airport', 'ar_name' => 'مطار بورنموث', 'school_branch_id' => 23, 'fee' => 60.00, 'created_at' => '2025-04-03 02:45:09', 'updated_at' => '2025-04-03 02:45:09'],
            ['id' => 37, 'name' => 'Southampton Airport', 'ar_name' => 'مطار ساوثهامبتون', 'school_branch_id' => 23, 'fee' => 140.00, 'created_at' => '2025-04-03 02:45:25', 'updated_at' => '2025-04-03 02:45:25'],
            ['id' => 38, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 23, 'fee' => 270.00, 'created_at' => '2025-04-03 02:45:44', 'updated_at' => '2025-04-03 02:45:44'],
            ['id' => 39, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 23, 'fee' => 285.00, 'created_at' => '2025-04-03 02:46:01', 'updated_at' => '2025-04-03 02:46:01'],
            ['id' => 40, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 23, 'fee' => 285.00, 'created_at' => '2025-04-03 02:46:27', 'updated_at' => '2025-04-03 02:46:27'],
            ['id' => 41, 'name' => 'Central London Airport', 'ar_name' => 'مطار لندن المركزي', 'school_branch_id' => 23, 'fee' => 315.00, 'created_at' => '2025-04-03 02:46:43', 'updated_at' => '2025-04-03 02:46:43'],
            ['id' => 42, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 23, 'fee' => 330.00, 'created_at' => '2025-04-03 02:47:00', 'updated_at' => '2025-04-03 02:47:00'],
            ['id' => 43, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 19, 'fee' => 100.00, 'created_at' => '2025-04-03 02:53:08', 'updated_at' => '2025-04-03 02:53:08'],
            ['id' => 44, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 19, 'fee' => 220.00, 'created_at' => '2025-04-03 02:53:24', 'updated_at' => '2025-04-03 02:53:24'],
            ['id' => 45, 'name' => 'St Pancras Station', 'ar_name' => 'محطة سانت بانكراس', 'school_branch_id' => 19, 'fee' => 225.00, 'created_at' => '2025-04-03 02:53:57', 'updated_at' => '2025-04-03 02:53:57'],
            ['id' => 46, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 19, 'fee' => 225.00, 'created_at' => '2025-04-03 02:54:12', 'updated_at' => '2025-04-03 02:54:12'],
            ['id' => 47, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 19, 'fee' => 145.00, 'created_at' => '2025-04-03 02:54:23', 'updated_at' => '2025-04-03 02:54:23'],
            ['id' => 48, 'name' => 'Manchester Airport', 'ar_name' => 'مطار مانشستر', 'school_branch_id' => 18, 'fee' => 125.00, 'created_at' => '2025-04-03 02:58:02', 'updated_at' => '2025-04-03 02:58:02'],
            ['id' => 49, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 9, 'fee' => 290.00, 'created_at' => '2025-04-03 02:58:32', 'updated_at' => '2025-04-03 02:58:32'],
            ['id' => 50, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 24, 'fee' => 140.00, 'created_at' => '2025-04-03 03:03:54', 'updated_at' => '2025-04-03 03:03:54'],
            ['id' => 51, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 24, 'fee' => 160.00, 'created_at' => '2025-04-03 03:04:20', 'updated_at' => '2025-04-03 03:04:20'],
            ['id' => 52, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 24, 'fee' => 140.00, 'created_at' => '2025-04-03 03:04:33', 'updated_at' => '2025-04-03 03:04:33'],
            ['id' => 53, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 24, 'fee' => 165.00, 'created_at' => '2025-04-03 03:04:46', 'updated_at' => '2025-04-03 03:04:46'],
            ['id' => 54, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 17, 'fee' => 140.00, 'created_at' => '2025-04-03 03:05:16', 'updated_at' => '2025-04-03 03:05:16'],
            ['id' => 55, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 10, 'fee' => 179.00, 'created_at' => '2025-04-03 03:05:48', 'updated_at' => '2025-04-03 03:05:48'],
            ['id' => 56, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 10, 'fee' => 279.00, 'created_at' => '2025-04-03 03:06:10', 'updated_at' => '2025-04-03 03:06:10'],
            ['id' => 57, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 10, 'fee' => 189.00, 'created_at' => '2025-04-03 03:06:22', 'updated_at' => '2025-04-03 03:06:22'],
            ['id' => 58, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 10, 'fee' => 205.00, 'created_at' => '2025-04-03 03:06:38', 'updated_at' => '2025-04-03 03:06:38'],
            ['id' => 59, 'name' => 'Manchester Airport', 'ar_name' => 'مطار مانشستر', 'school_branch_id' => 16, 'fee' => 80.00, 'created_at' => '2025-04-03 03:07:14', 'updated_at' => '2025-04-03 03:07:14'],
            ['id' => 60, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 14, 'fee' => 263.00, 'created_at' => '2025-04-03 03:07:44', 'updated_at' => '2025-04-03 03:07:44'],
            ['id' => 61, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 14, 'fee' => 276.00, 'created_at' => '2025-04-03 03:07:56', 'updated_at' => '2025-04-03 03:07:56'],
            ['id' => 62, 'name' => 'Bournemouth Airport', 'ar_name' => 'مطار بورنموث', 'school_branch_id' => 14, 'fee' => 100.00, 'created_at' => '2025-04-03 03:08:08', 'updated_at' => '2025-04-03 03:08:08'],
            ['id' => 63, 'name' => 'Southampton Airport', 'ar_name' => 'مطار ساوثهامبتون', 'school_branch_id' => 14, 'fee' => 158.00, 'created_at' => '2025-04-03 03:08:22', 'updated_at' => '2025-04-03 03:08:22'],
            ['id' => 64, 'name' => 'Central London Airport', 'ar_name' => 'مطار لندن المركزي', 'school_branch_id' => 14, 'fee' => 314.00, 'created_at' => '2025-04-03 03:08:33', 'updated_at' => '2025-04-03 03:08:33'],
            ['id' => 65, 'name' => 'London Stansted Airport', 'ar_name' => 'مطار لندن ستانستيد', 'school_branch_id' => 14, 'fee' => 326.00, 'created_at' => '2025-04-03 03:08:55', 'updated_at' => '2025-04-03 03:08:55'],
            ['id' => 66, 'name' => 'London Luton Airport', 'ar_name' => 'مطار لندن لوتون', 'school_branch_id' => 14, 'fee' => 276.00, 'created_at' => '2025-04-03 03:09:13', 'updated_at' => '2025-04-03 03:09:13'],
            ['id' => 67, 'name' => 'Bristol', 'ar_name' => 'Bristol', 'school_branch_id' => 14, 'fee' => 327.00, 'created_at' => '2025-04-03 03:09:59', 'updated_at' => '2025-04-03 03:09:59'],
            ['id' => 68, 'name' => 'London City Airport', 'ar_name' => 'مطار لندن سيتي', 'school_branch_id' => 15, 'fee' => 365.00, 'created_at' => '2025-04-03 03:10:42', 'updated_at' => '2025-04-03 03:10:42'],
            ['id' => 69, 'name' => 'Manchester Airport', 'ar_name' => 'مطار مانشستر', 'school_branch_id' => 15, 'fee' => 85.00, 'created_at' => '2025-04-03 03:10:54', 'updated_at' => '2025-04-03 03:10:54'],
            ['id' => 70, 'name' => 'Liverpool Airport', 'ar_name' => 'مطار ليفربول', 'school_branch_id' => 15, 'fee' => 115.00, 'created_at' => '2025-04-03 03:11:07', 'updated_at' => '2025-04-03 03:11:07'],
            ['id' => 71, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 12, 'fee' => 180.00, 'created_at' => '2025-04-03 03:11:30', 'updated_at' => '2025-04-03 03:11:30'],
            ['id' => 72, 'name' => 'London Heathrow Airport', 'ar_name' => 'مطار لندن هيثرو', 'school_branch_id' => 25, 'fee' => 130.00, 'created_at' => '2025-04-03 03:11:56', 'updated_at' => '2025-04-03 03:11:56'],
            ['id' => 73, 'name' => 'London Gatwick Airport', 'ar_name' => 'مطار لندن غاتويك', 'school_branch_id' => 25, 'fee' => 140.00, 'created_at' => '2025-04-03 03:12:13', 'updated_at' => '2025-04-03 03:12:13'],
        ];

        foreach ($rows as $row) {
            DB::table('language_school_pickups')->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'branch_id' => (int) $row['school_branch_id'],
                    'route' => $row['name'],
                    'price' => $row['fee'],
                    'notes' => $row['ar_name'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                ]
            );
        }
    }
}
