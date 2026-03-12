<?php

namespace Tests\Feature;

use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SsoAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.sso.base_url', 'https://sso.poltera.ac.id');
        config()->set('services.sso.client_id', 'test-client');
        config()->set('services.sso.client_secret', 'test-secret');
        config()->set('services.sso.redirect_uri', 'http://localhost/auth/sso/callback');
        config()->set('services.sso.allowed_timestamp_skew', 300);
    }

    public function test_happy_path_login_works(): void
    {
        Http::fake([
            'https://sso.poltera.ac.id/oauth/token' => Http::response([
                'access_token' => 'token-123',
                'token_type' => 'Bearer',
            ], 200),
            'https://sso.poltera.ac.id/oauth/userinfo' => Http::response([
                'sub' => 'sub-123',
                'email' => 'pegawai@poltera.ac.id',
                'name' => 'Pegawai Poltera',
                'department' => 'Akademik',
                'program' => 'Teknik Informatika',
                'support_unit' => 'UPT TIK',
            ], 200),
        ]);

        $this->get('/auth/sso/redirect')->assertRedirect();
        $state = session('sso.oauth_state');

        $response = $this->get('/auth/sso/callback?code=ok-code&state='.$state);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'sso_sub' => 'sub-123',
            'email' => 'pegawai@poltera.ac.id',
            'sso_department' => 'Akademik',
            'sso_program' => 'Teknik Informatika',
            'sso_support_unit' => 'UPT TIK',
        ]);
    }

    public function test_state_mismatch_is_rejected(): void
    {
        $response = $this->withSession(['sso.oauth_state' => 'expected-state'])
            ->get('/auth/sso/callback?code=ok-code&state=wrong-state');

        $response->assertForbidden();
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $response = $this->call(
            'POST',
            '/auth/sso/backchannel/logout',
            [],
            [],
            [],
            ['HTTP_X-SSO-Signature' => 'invalid-signature', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['sub' => 'sub-1', 'timestamp' => now()->timestamp])
        );

        $response->assertStatus(401);
    }

    public function test_expired_timestamp_is_rejected(): void
    {
        $payload = [
            'sub' => 'sub-1',
            'timestamp' => now()->subMinutes(20)->timestamp,
        ];

        $raw = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $raw, 'test-secret');

        $response = $this->call(
            'POST',
            '/auth/sso/backchannel/logout',
            [],
            [],
            [],
            ['HTTP_X-SSO-Signature' => $signature, 'CONTENT_TYPE' => 'application/json'],
            $raw
        );

        $response->assertStatus(401);
    }

    public function test_backchannel_logout_revokes_local_sessions_and_returns_200(): void
    {
        $user = $this->createLegacyUser();
        $user->sso_sub = 'sub-logout';
        $user->save();

        DB::table('sessions')->insert([
            'id' => 'sid-logout-1',
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => 'payload',
            'last_activity' => now()->timestamp,
        ]);

        $payload = [
            'sub' => 'sub-logout',
            'sid' => 'sid-logout-1',
            'timestamp' => now()->timestamp,
        ];

        $raw = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $raw, 'test-secret');

        $response = $this->call(
            'POST',
            '/auth/sso/backchannel/logout',
            [],
            [],
            [],
            ['HTTP_X-SSO-Signature' => $signature, 'CONTENT_TYPE' => 'application/json'],
            $raw
        );

        $response->assertOk();
        $this->assertDatabaseMissing('sessions', ['id' => 'sid-logout-1']);
    }

    private function createLegacyUser(): User
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
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
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
