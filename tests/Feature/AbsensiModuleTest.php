<?php

namespace Tests\Feature;

use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AbsensiModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_absensi_routes_are_protected_for_guests(): void
    {
        $this->get('/absensis')->assertRedirect('/login');
        $this->get('/monitor_absensi')->assertRedirect('/login');
        $this->post('/absensis/pulang', ['id' => 1])->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_absensi_and_check_in_out(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/absensis')
            ->assertOk()
            ->assertSee('Absensi');

        $this->actingAs($user)
            ->post('/absensis')
            ->assertRedirect('/absensis');

        $absensiId = DB::table('absensis')->where('user_id', $user->id)->value('id');

        $this->assertNotNull($absensiId);

        $this->actingAs($user)
            ->post('/absensis/pulang', ['id' => $absensiId])
            ->assertOk();

        $this->assertDatabaseHas('absensis', [
            'id' => $absensiId,
            'user_id' => $user->id,
        ]);
    }

    private function createUser(): User
    {
        if (TipeUser::query()->count() === 0) {
            DB::table('tipe_users')->insert([
                'nama' => 'Default',
                'deskripsi' => 'Default',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (UnitKerja::query()->count() === 0) {
            DB::table('unit_kerjas')->insert([
                'nama' => 'Default',
                'deskripsi' => 'Default',
                'tipe' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $tipe = TipeUser::query()->firstOrFail();
        $unit = UnitKerja::query()->firstOrFail();

        return User::create([
            'nama' => 'Absensi User',
            'email' => 'absensi.user@example.com',
            'username' => 'absensiuser',
            'password' => Hash::make('password'),
            'image_path' => '-',
            'api_token' => '',
            'fcm_id' => '',
            'tipe_user_id' => $tipe->id,
            'unit_kerja_id' => $unit->id,
            'jenis_kelamin' => '-',
            'alamat' => '-',
            'agama' => '-',
            'tempat_lahir' => '-',
            'tanggal_lahir' => '-',
            'no_hp' => '-',
        ]);
    }
}
