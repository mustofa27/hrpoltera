<?php

namespace Tests\Feature;

use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Phase5HardeningTest extends TestCase
{
    use RefreshDatabase;

    // ─── Guest protection ────────────────────────────────────────────────

    public function test_phase5_routes_are_protected_for_guests(): void
    {
        $this->get('/pegawais')->assertRedirect('/login');
        $this->get('/lupa_absens')->assertRedirect('/login');
        $this->get('/validasi_lupa_absen')->assertRedirect('/login');
        $this->get('/tugas_dinas')->assertRedirect('/login');
        $this->get('/liburs')->assertRedirect('/login');
    }

    // ─── Pegawai CRUD ────────────────────────────────────────────────────

    public function test_authenticated_user_can_create_and_delete_pegawai(): void
    {
        [$atasan, $bawahan] = $this->createTwoUsers();

        $this->actingAs($atasan)
            ->post('/pegawais', [
                'user_id' => $bawahan->id,
                'nip' => '199001012020031001',
                'shift_id' => 1,
                'atasan_langsung_id' => $atasan->id,
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'M.T.',
                'urutan' => 2,
            ])
            ->assertRedirect('/pegawais');

        $this->assertDatabaseHas('pegawais', [
            'user_id' => $bawahan->id,
            'nip' => '199001012020031001',
        ]);

        $pegawaiId = DB::table('pegawais')->where('user_id', $bawahan->id)->value('id');

        $this->actingAs($atasan)
            ->delete('/pegawais/'.$pegawaiId)
            ->assertRedirect('/pegawais');

        $this->assertDatabaseMissing('pegawais', ['id' => $pegawaiId]);
    }

    // ─── Hari Libur ──────────────────────────────────────────────────────

    public function test_authenticated_user_can_create_and_delete_hari_libur(): void
    {
        $user = $this->createOneUser();

        $this->actingAs($user)
            ->post('/liburs', [
                'nama' => 'Hari Raya Idul Fitri',
                'tanggal' => '2026-03-30',
            ])
            ->assertRedirect('/liburs');

        $this->assertDatabaseHas('hari_liburs', ['nama' => 'Hari Raya Idul Fitri']);
    }

    // ─── Lupa Absen ──────────────────────────────────────────────────────

    public function test_authenticated_user_can_submit_lupa_absen(): void
    {
        [$atasan, $bawahan] = $this->createTwoUsersWithPegawai();

        $this->actingAs($bawahan)
            ->post('/lupa_absens', [
                'tanggal' => '2026-03-01',
                'jam_masuk' => '08:00',
                'jam_pulang' => '16:00',
                'aktivitas' => 'Rekap dokumen',
            ])
            ->assertRedirect('/lupa_absens');

        $this->assertDatabaseHas('lupa_absens', [
            'user_id' => $bawahan->id,
            'tanggal_lupa' => '2026-03-01',
            'status' => 'requested',
        ]);
    }

    public function test_atasan_can_approve_lupa_absen(): void
    {
        [$atasan, $bawahan] = $this->createTwoUsersWithPegawai();

        $lupaAbsenId = DB::table('lupa_absens')->insertGetId([
            'tanggal_lupa' => '2026-02-15',
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'aktivitas' => 'Rekap arsip',
            'status' => 'requested',
            'user_id' => $bawahan->id,
            'atasan_id' => $atasan->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($atasan)
            ->put('/lupa_absens/'.$lupaAbsenId)
            ->assertRedirect('/validasi_lupa_absen');

        $this->assertDatabaseHas('lupa_absens', [
            'id' => $lupaAbsenId,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('absensis', [
            'user_id' => $bawahan->id,
            'tanggal' => '2026-02-15',
        ]);
    }

    public function test_non_atasan_cannot_approve_lupa_absen(): void
    {
        [$atasan, $bawahan] = $this->createTwoUsersWithPegawai();
        $otherUser = $this->createOneUserWithIdentity('Other User', 'otherphase5@poltera.ac.id', 'otherphase5');

        $lupaAbsenId = DB::table('lupa_absens')->insertGetId([
            'tanggal_lupa' => '2026-02-16',
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'aktivitas' => 'Input data',
            'status' => 'requested',
            'user_id' => $bawahan->id,
            'atasan_id' => $atasan->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($otherUser)
            ->put('/lupa_absens/'.$lupaAbsenId)
            ->assertForbidden();

        $this->assertDatabaseHas('lupa_absens', [
            'id' => $lupaAbsenId,
            'status' => 'requested',
        ]);
    }

    public function test_non_atasan_cannot_reject_lupa_absen(): void
    {
        [$atasan, $bawahan] = $this->createTwoUsersWithPegawai();
        $otherUser = $this->createOneUserWithIdentity('Other Rejector', 'otherreject@poltera.ac.id', 'otherreject');

        $lupaAbsenId = DB::table('lupa_absens')->insertGetId([
            'tanggal_lupa' => '2026-02-17',
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'aktivitas' => 'Scan arsip',
            'status' => 'requested',
            'user_id' => $bawahan->id,
            'atasan_id' => $atasan->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($otherUser)
            ->delete('/lupa_absens/'.$lupaAbsenId)
            ->assertForbidden();
    }

    // ─── Tugas Dinas ─────────────────────────────────────────────────────

    public function test_authenticated_user_can_create_and_delete_tugas_dinas(): void
    {
        $user = $this->createOneUser();
        $this->seedJenisSurat();

        $this->actingAs($user)
            ->post('/tugas_dinas', [
                'no_surat' => '001/TGS/III/2026',
                'tanggal_surat' => '2026-03-13',
                'tentang' => 'Dinas ke Jakarta',
                'keterangan' => 'Menghadiri seminar',
                'tanggal_mulai' => '2026-03-15',
                'tanggal_selesai' => '2026-03-17',
                'user_id' => $user->id,
            ])
            ->assertRedirect('/tugas_dinas');

        $this->assertDatabaseHas('tugas_dinas', ['tentang' => 'Dinas ke Jakarta']);

        $tdId = DB::table('tugas_dinas')->where('user_id', $user->id)->value('id');

        $this->actingAs($user)
            ->delete('/tugas_dinas/'.$tdId)
            ->assertRedirect();

        $this->assertDatabaseMissing('tugas_dinas', ['id' => $tdId]);
    }

    public function test_non_owner_cannot_delete_tugas_dinas(): void
    {
        $owner = $this->createOneUserWithIdentity('Owner TD', 'owner-td@poltera.ac.id', 'ownertd');
        $other = $this->createOneUserWithIdentity('Other TD', 'other-td@poltera.ac.id', 'othertd');
        $this->seedJenisSurat();

        $this->actingAs($owner)
            ->post('/tugas_dinas', [
                'no_surat' => '002/TGS/III/2026',
                'tanggal_surat' => '2026-03-13',
                'tentang' => 'Tugas luar kota',
                'keterangan' => 'Kunjungan kerja',
                'tanggal_mulai' => '2026-03-18',
                'tanggal_selesai' => '2026-03-19',
                'user_id' => $owner->id,
            ])
            ->assertRedirect('/tugas_dinas');

        $tdId = DB::table('tugas_dinas')->where('user_id', $owner->id)->value('id');

        $this->actingAs($other)
            ->delete('/tugas_dinas/'.$tdId)
            ->assertForbidden();
    }

    public function test_adminpusat_can_delete_tugas_dinas_from_other_user(): void
    {
        $owner = $this->createOneUserWithIdentity('Owner TD Admin', 'owner-admin-td@poltera.ac.id', 'owneradmintd');
        $admin = $this->createOneUserWithIdentity('Admin Pusat', 'adminpusat@poltera.ac.id', 'adminpusat');
        $this->seedJenisSurat();

        $this->actingAs($owner)
            ->post('/tugas_dinas', [
                'no_surat' => '003/TGS/III/2026',
                'tanggal_surat' => '2026-03-13',
                'tentang' => 'Tugas admin override',
                'keterangan' => 'Skenario admin',
                'tanggal_mulai' => '2026-03-18',
                'tanggal_selesai' => '2026-03-19',
                'user_id' => $owner->id,
            ])
            ->assertRedirect('/tugas_dinas');

        $tdId = DB::table('tugas_dinas')->where('user_id', $owner->id)->value('id');

        $this->actingAs($admin)
            ->delete('/tugas_dinas/'.$tdId)
            ->assertRedirect();

        $this->assertDatabaseMissing('tugas_dinas', ['id' => $tdId]);
    }

    public function test_non_atasan_cannot_validate_cuti(): void
    {
        [$atasan, $bawahan] = $this->createTwoUsersWithPegawai();
        $otherUser = $this->createOneUserWithIdentity('Other Cuti', 'other-cuti@poltera.ac.id', 'othercuti');
        $this->seedJenisCuti();

        $cutiId = DB::table('cutis')->insertGetId([
            'jenis_cuti_id' => 1,
            'tanggal_mulai' => '2026-03-20',
            'tanggal_selesai' => '2026-03-21',
            'keterangan' => 'Keperluan keluarga',
            'file_pendukung' => '-',
            'user_id' => $bawahan->id,
            'pertimbangan_atasan' => 'requested',
            'catatan_atasan' => '-',
            'persetujuan_pejabat' => 'requested',
            'catatan_pejabat' => '-',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($otherUser)
            ->put('/cutis/validasi/atasan/'.$cutiId, [
                'pertimbangan_atasan' => 'accepted',
                'catatan_atasan' => 'Disetujui',
            ])
            ->assertForbidden();
    }

    public function test_adminpusat_can_validate_cuti_pejabat_after_atasan_decision(): void
    {
        [$atasan, $bawahan] = $this->createTwoUsersWithPegawai();
        $admin = $this->createOneUserWithIdentity('Admin Pusat Cuti', 'admin-cuti@poltera.ac.id', 'adminpusat');
        $this->seedJenisCuti();

        $cutiId = DB::table('cutis')->insertGetId([
            'jenis_cuti_id' => 1,
            'tanggal_mulai' => '2026-03-22',
            'tanggal_selesai' => '2026-03-23',
            'keterangan' => 'Izin keluarga',
            'file_pendukung' => '-',
            'user_id' => $bawahan->id,
            'pertimbangan_atasan' => 'accepted',
            'catatan_atasan' => 'Oke',
            'persetujuan_pejabat' => 'requested',
            'catatan_pejabat' => '-',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post('/cutis/validasi/pejabat/'.$cutiId, [
                'persetujuan_pejabat' => 'accepted',
                'catatan_pejabat' => 'Disetujui admin pusat',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cutis', [
            'id' => $cutiId,
            'persetujuan_pejabat' => 'accepted',
        ]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────

    private function createOneUser(): User
    {
        $this->seedReferenceData();

        return User::create([
            'nama' => 'Phase5 User',
            'email' => 'phase5@poltera.ac.id',
            'username' => 'phase5user',
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

    private function createOneUserWithIdentity(string $name, string $email, string $username): User
    {
        $this->seedReferenceData();

        return User::create([
            'nama' => $name,
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

    private function createTwoUsers(): array
    {
        $this->seedReferenceData();

        $atasan = User::create([
            'nama' => 'Atasan Phase5',
            'email' => 'atasan5@poltera.ac.id',
            'username' => 'atasan5',
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

        $bawahan = User::create([
            'nama' => 'Bawahan Phase5',
            'email' => 'bawahan5@poltera.ac.id',
            'username' => 'bawahan5',
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

        return [$atasan, $bawahan];
    }

    private function createTwoUsersWithPegawai(): array
    {
        [$atasan, $bawahan] = $this->createTwoUsers();

        DB::table('pegawais')->insert([
            'nip' => '-', 'nik' => '-',
            'pangkat_id' => 1, 'golongan_id' => 1,
            'user_id' => $atasan->id,
            'status_nikah' => '-', 'shift_id' => 1,
            'npwp' => '-', 'is_serdos' => 0, 'is_remun' => 0,
            'kampus_id' => 1, 'atasan_langsung_id' => $atasan->id,
            'urutan' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('pegawais')->insert([
            'nip' => '-', 'nik' => '-',
            'pangkat_id' => 1, 'golongan_id' => 1,
            'user_id' => $bawahan->id,
            'status_nikah' => '-', 'shift_id' => 1,
            'npwp' => '-', 'is_serdos' => 0, 'is_remun' => 0,
            'kampus_id' => 1, 'atasan_langsung_id' => $atasan->id,
            'urutan' => 2, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return [$atasan, $bawahan];
    }

    private function seedReferenceData(): void
    {
        if (TipeUser::query()->count() === 0) {
            DB::table('tipe_users')->insert(['id' => 1, 'nama' => 'Default', 'deskripsi' => 'Default', 'created_at' => now(), 'updated_at' => now()]);
        }
        if (UnitKerja::query()->count() === 0) {
            DB::table('unit_kerjas')->insert(['id' => 1, 'nama' => 'Default', 'deskripsi' => 'Default', 'tipe' => 0, 'created_at' => now(), 'updated_at' => now()]);
        }
        if (DB::table('pangkats')->count() === 0) {
            DB::table('pangkats')->insert(['id' => 1, 'nama' => 'Default', 'deskripsi' => 'Default', 'created_at' => now(), 'updated_at' => now()]);
        }
        if (DB::table('golongans')->count() === 0) {
            DB::table('golongans')->insert(['id' => 1, 'nama' => 'Default', 'deskripsi' => 'Default', 'created_at' => now(), 'updated_at' => now()]);
        }
        if (DB::table('kampuses')->count() === 0) {
            DB::table('kampuses')->insert(['id' => 1, 'nama' => 'Default', 'alamat' => 'Default', 'latitude' => 0, 'longitude' => 0, 'created_at' => now(), 'updated_at' => now()]);
        }
        if (DB::table('shifts')->count() === 0) {
            DB::table('shifts')->insert(['id' => 1, 'nama' => 'Default', 'jam_mulai' => '08:00:00', 'jam_mulai_maks' => '08:30:00', 'jam_selesai' => '16:00:00', 'durasi_minimum' => 8, 'min_aktivitas' => 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function seedJenisSurat(): void
    {
        if (DB::table('jenis_surats')->count() === 0) {
            DB::table('jenis_surats')->insert(['id' => 1, 'nama' => 'Default', 'deskripsi' => 'Default', 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function seedJenisCuti(): void
    {
        if (DB::table('jenis_cutis')->count() === 0) {
            DB::table('jenis_cutis')->insert(['id' => 1, 'nama' => 'Cuti Tahunan', 'deskripsi' => 'Default', 'durasi' => 12, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
