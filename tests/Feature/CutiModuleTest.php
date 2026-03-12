<?php

namespace Tests\Feature;

use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CutiModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_cuti_routes_are_protected_for_guests(): void
    {
        $this->get('/cutis')->assertRedirect('/login');
        $this->get('/monitor_cuti')->assertRedirect('/login');
        $this->get('/validasi_cuti')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_submit_and_validate_cuti_flow(): void
    {
        $atasan = $this->createUser('atasan@poltera.ac.id', 'atasan');
        $bawahan = $this->createUser('bawahan@poltera.ac.id', 'bawahan');

        DB::table('pegawais')->insert([
            'nip' => '-',
            'nik' => '-',
            'pangkat_id' => 1,
            'golongan_id' => 1,
            'user_id' => $bawahan->id,
            'gelar_depan' => null,
            'gelar_belakang' => null,
            'status_nikah' => '-',
            'shift_id' => 1,
            'npwp' => '-',
            'is_serdos' => 0,
            'is_remun' => 0,
            'kampus_id' => 1,
            'kode_device' => null,
            'atasan_langsung_id' => $atasan->id,
            'urutan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($bawahan)
            ->post('/cutis', [
                'jenis_cuti_id' => 1,
                'tanggal_mulai' => '2026-03-12',
                'tanggal_selesai' => '2026-03-13',
                'keterangan' => 'Sakit',
            ])
            ->assertRedirect('/cutis');

        $cutiId = DB::table('cutis')->where('user_id', $bawahan->id)->value('id');
        $this->assertNotNull($cutiId);

        $this->actingAs($atasan)
            ->put('/cutis/validasi/atasan/'.$cutiId, [
                'pertimbangan_atasan' => 'accepted',
                'catatan_atasan' => 'Disetujui atasan',
            ])
            ->assertRedirect();

        $this->actingAs($atasan)
            ->post('/cutis/validasi/pejabat/'.$cutiId, [
                'persetujuan_pejabat' => 'accepted',
                'catatan_pejabat' => 'Disetujui pejabat',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cutis', [
            'id' => $cutiId,
            'pertimbangan_atasan' => 'accepted',
            'persetujuan_pejabat' => 'accepted',
        ]);
    }

    private function createUser(string $email, string $username): User
    {
        $this->seedReferenceData();

        return User::create([
            'nama' => 'User '.strtoupper($username),
            'email' => $email,
            'username' => $username,
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

        if (DB::table('jenis_cutis')->count() === 0) {
            DB::table('jenis_cutis')->insert([
                'id' => 1,
                'nama' => 'Cuti Tahunan',
                'deskripsi' => 'Default',
                'durasi' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('pangkats')->count() === 0) {
            DB::table('pangkats')->insert([
                'id' => 1,
                'nama' => 'Pangkat Default',
                'deskripsi' => 'Default',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('golongans')->count() === 0) {
            DB::table('golongans')->insert([
                'id' => 1,
                'nama' => 'Golongan Default',
                'deskripsi' => 'Default',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('kampuses')->count() === 0) {
            DB::table('kampuses')->insert([
                'id' => 1,
                'nama' => 'Kampus Default',
                'alamat' => 'Default',
                'latitude' => 0,
                'longitude' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('shifts')->count() === 0) {
            DB::table('shifts')->insert([
                'id' => 1,
                'nama' => 'Shift Default',
                'jam_mulai' => '08:00:00',
                'jam_mulai_maks' => '08:30:00',
                'jam_selesai' => '16:00:00',
                'durasi_minimum' => 8,
                'min_aktivitas' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
