<?php

namespace Tests\Feature;

use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AktivitasHarianModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_aktivitas_routes_are_protected_for_guests(): void
    {
        $this->get('/aktivitas_harians')->assertRedirect('/login');
        $this->get('/monitor')->assertRedirect('/login');
        $this->get('/aktivitas/past_date')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_submit_and_monitor_aktivitas(): void
    {
        $atasan = $this->createUser('atasan-aktivitas@poltera.ac.id', 'atasanaktivitas');
        $bawahan = $this->createUser('bawahan-aktivitas@poltera.ac.id', 'bawahanaktivitas');

        $this->createPegawai($atasan->id, $atasan->id, 1);
        $this->createPegawai($bawahan->id, $atasan->id, 2);
        $this->createAbsensiForToday($bawahan->id);

        $this->actingAs($bawahan)
            ->post('/aktivitas_harians', [
                'kegiatan' => 'Menyusun laporan harian',
                'status' => 'Selesai',
            ])
            ->assertRedirect('/aktivitas_harians');

        $this->assertDatabaseHas('aktivitas_harians', [
            'user_id' => $bawahan->id,
            'kegiatan' => 'Menyusun laporan harian',
            'status' => 'Selesai',
        ]);

        $this->actingAs($bawahan)
            ->post('/aktivitas/store_past_date', [
                'tanggal' => '2026-03-01',
                'jam' => '09:00:00',
                'kegiatan' => 'Rekap kegiatan lampau',
                'status' => 'Selesai',
            ])
            ->assertRedirect('/aktivitas_harians');

        $this->actingAs($bawahan)->get('/aktivitas_harians')->assertOk();
        $this->actingAs($bawahan)->get('/aktivitas/past_date')->assertOk();

        $this->actingAs($atasan)
            ->get('/monitor')
            ->assertOk()
            ->assertSee('Menyusun laporan harian');
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

    private function createPegawai(int $userId, int $atasanId, int $urutan): void
    {
        DB::table('pegawais')->insert([
            'nip' => '-',
            'nik' => '-',
            'pangkat_id' => 1,
            'golongan_id' => 1,
            'user_id' => $userId,
            'gelar_depan' => null,
            'gelar_belakang' => null,
            'status_nikah' => '-',
            'shift_id' => 1,
            'npwp' => '-',
            'is_serdos' => 0,
            'is_remun' => 0,
            'kampus_id' => 1,
            'kode_device' => null,
            'atasan_langsung_id' => $atasanId,
            'urutan' => $urutan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createAbsensiForToday(int $userId): void
    {
        DB::table('absensis')->insert([
            'user_id' => $userId,
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'kampus_id' => 1,
            'terlambat' => 0,
            'pulang_sebelum_waktu' => 0,
            'alasan_ganti_kampus' => '-',
            'ttd_masuk_path' => '-',
            'ttd_pulang_path' => '-',
            'created_at' => now(),
            'updated_at' => now(),
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
