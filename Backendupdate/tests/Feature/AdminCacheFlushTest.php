<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCacheFlushTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_flush_api_cache_from_header_action(): void
    {
        $path = storage_path('framework/cache/api_responses');
        File::ensureDirectoryExists($path);
        File::put($path . '/sample.json', '{"cached":true}');

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.cache.flush'));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertFileDoesNotExist($path . '/sample.json');
    }
}
