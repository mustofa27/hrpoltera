<?php

namespace Tests\Feature;

use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MasterDataModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_data_routes_are_protected_for_guests(): void
    {
        $this->get('/jabatans')->assertRedirect('/login');
        $this->get('/golongans')->assertRedirect('/login');
        $this->get('/pangkats')->assertRedirect('/login');
        $this->get('/unit_kerjas')->assertRedirect('/login');
        $this->get('/kampuses')->assertRedirect('/login');
        $this->get('/shifts')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_core_master_data_records(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->post('/jabatans', [
                'nama' => 'Jabatan Uji',
                'deskripsi' => 'Deskripsi Jabatan Uji',
            ])
            ->assertRedirect('/jabatans');

        $this->actingAs($user)
            ->post('/golongans', [
                'nama' => 'Golongan Uji',
                'deskripsi' => 'Deskripsi Golongan Uji',
            ])
            ->assertRedirect('/golongans');

        $this->actingAs($user)
            ->post('/pangkats', [
                'nama' => 'Pangkat Uji',
                'deskripsi' => 'Deskripsi Pangkat Uji',
            ])
            ->assertRedirect('/pangkats');

        $this->actingAs($user)
            ->post('/unit_kerjas', [
                'nama' => 'Unit Uji',
                'deskripsi' => 'Deskripsi Unit Uji',
                'tipe' => 1,
            ])
            ->assertRedirect('/unit_kerjas');

        $this->actingAs($user)
            ->post('/kampuses', [
                'nama' => 'Kampus Uji',
                'alamat' => 'Alamat Uji',
                'latitude' => -7.24917,
                'longitude' => 112.75083,
            ])
            ->assertRedirect('/kampuses');

        $this->actingAs($user)
            ->post('/shifts', [
                'nama' => 'Shift Uji',
                'jam_mulai' => '08:00',
                'jam_mulai_maks' => '08:30',
                'jam_selesai' => '16:00',
                'durasi_minimum' => 8,
                'min_aktivitas' => 1,
            ])
            ->assertRedirect('/shifts');

        $this->assertDatabaseHas('jabatans', ['nama' => 'Jabatan Uji']);
        $this->assertDatabaseHas('golongans', ['nama' => 'Golongan Uji']);
        $this->assertDatabaseHas('pangkats', ['nama' => 'Pangkat Uji']);
        $this->assertDatabaseHas('unit_kerjas', ['nama' => 'Unit Uji']);
        $this->assertDatabaseHas('kampuses', ['nama' => 'Kampus Uji']);
        $this->assertDatabaseHas('shifts', ['nama' => 'Shift Uji']);
    }

    private function createUser(): User
    {
        $this->seedReferenceData();

        return User::create([
            'nama' => 'Master Data User',
            'email' => 'master-data@poltera.ac.id',
            'username' => 'masterdata',
            'password' => Hash::make('password'),
            'image_path' => '-',
            'api_token' => '',
            'fcm_id' => '',
            'tipe_user_id' => 1,
            'unit_kerja_id' => 1,
            'jenis_kelamin' => '-',
            'alamat' => '-',
            'agama' => '-',
            'tempat_lahir' => '-',
            'tanggal_lahir' => '-',
            'no_hp' => '-',
        ]);
    }

    private function seedReferenceData(): void
    {
        if (TipeUser::query()->count() === 0) {
            DB::table('tipe_users')->insert([
                'id' => 1,
                'nama' => 'Default',
                'deskripsi' => 'Default',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (UnitKerja::query()->count() === 0) {
            DB::table('unit_kerjas')->insert([
                'id' => 1,
                'nama' => 'Default',
                'deskripsi' => 'Default',
                'tipe' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
