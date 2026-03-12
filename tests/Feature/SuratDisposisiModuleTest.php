<?php

namespace Tests\Feature;

use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratDisposisiModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_surat_disposisi_routes_are_protected_for_guests(): void
    {
        $this->get('/surat')->assertRedirect('/login');
        $this->get('/surat/create')->assertRedirect('/login');
        $this->get('/disposisi')->assertRedirect('/login');
        $this->get('/disposisi/create')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_surat_and_disposisi(): void
    {
        Storage::fake('local');

        $userA = $this->createUser('a@example.com', 'usera');
        $userB = $this->createUser('b@example.com', 'userb');

        $this->actingAs($userA)
            ->post('/surat/store', [
                'pengirim' => 'Direktorat',
                'tanggal_surat' => '2026-03-12',
                'nomor_surat' => 'SRT-001',
                'perihal' => 'Undangan',
                'nomor_agenda' => 'AG-01',
                'tanggal_terima' => '2026-03-12',
                'sifat_surat' => 'Penting',
                'file' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect('/surat');

        $this->assertDatabaseHas('surats', [
            'pengirim' => 'Direktorat',
            'no_surat' => 'SRT-001',
        ]);

        $suratId = DB::table('surats')->where('no_surat', 'SRT-001')->value('id');

        $this->actingAs($userA)
            ->post('/disposisi/store', [
                'tanggal_disposisi' => '2026-03-12',
                'surat' => $suratId,
                'user' => $userB->id,
            ])
            ->assertRedirect('/disposisi');

        $this->assertDatabaseHas('disposisis', [
            'id_surat' => $suratId,
            'id_user_asal' => $userA->id,
            'id_user_tujuan' => $userB->id,
        ]);

        $this->actingAs($userA)->get('/surat')->assertOk()->assertSee('List Data Surat');
        $this->actingAs($userA)->get('/disposisi')->assertOk()->assertSee('List Data Disposisi');
    }

    private function createUser(string $email, string $username): User
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
            'nama' => 'Module User '.strtoupper($username),
            'email' => $email,
            'username' => $username,
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
