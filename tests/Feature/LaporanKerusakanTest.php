<?php

namespace Tests\Feature;

use App\Models\LaporanKerusakan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanKerusakanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_export_damage_report(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.kerusakan.store'), [
            'judul' => 'Lampu kamar rusak',
            'deskripsi' => 'Lampu plafon tidak menyala.',
            'gambar' => UploadedFile::fake()->image('lampu.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('laporan_kerusakans', ['judul' => 'Lampu kamar rusak']);
        Storage::disk('public')->assertExists(LaporanKerusakan::firstOrFail()->gambar);

        $this->actingAs($admin)->get(route('admin.kerusakan.export.pdf'))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
