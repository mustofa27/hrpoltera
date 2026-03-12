<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TipeUser;
use App\Models\UnitKerja;
use App\Models\User;
use App\Services\SsoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class SsoAuthController extends Controller
{
    public function redirect(Request $request, SsoService $ssoService)
    {
        $state = Str::random(40);
        $request->session()->put('sso.oauth_state', $state);

        return redirect()->away($ssoService->authorizationUrl($state));
    }

    public function callback(Request $request, SsoService $ssoService)
    {
        $expectedState = (string) $request->session()->pull('sso.oauth_state', '');
        $actualState = (string) $request->query('state', '');

        if ($expectedState === '' || $actualState === '' || ! hash_equals($expectedState, $actualState)) {
            abort(403, 'Invalid OAuth state.');
        }

        $code = (string) $request->query('code', '');

        if ($code === '') {
            abort(422, 'Missing authorization code.');
        }

        try {
            $tokenData = $ssoService->exchangeAuthorizationCode($code);
            $accessToken = (string) ($tokenData['access_token'] ?? '');

            if ($accessToken === '') {
                throw new RuntimeException('Missing access token in OAuth response.');
            }

            $userInfo = $ssoService->fetchUserInfo($accessToken);
        } catch (RuntimeException $exception) {
            report($exception);
            abort(502, 'SSO authentication failed.');
        }

        $user = $this->resolveLocalUserFromUserInfo($userInfo);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function logout(Request $request, SsoService $ssoService)
    {
        $currentUser = $request->user();

        if ($currentUser !== null && ! empty($currentUser->sso_sub)) {
            $payload = [
                'sub' => $currentUser->sso_sub,
                'sid' => $request->session()->getId(),
                'timestamp' => now()->timestamp,
            ];

            $signed = $ssoService->signPayload($payload);

            try {
                Http::withHeaders([
                    'X-SSO-Signature' => $signed['signature'],
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                    ->timeout(3)
                    ->post(rtrim((string) config('services.sso.base_url'), '/').'/sso/backchannel/logout', $payload);
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function resolveLocalUserFromUserInfo(array $userInfo): User
    {
        $sub = (string) ($userInfo['sub'] ?? '');

        if ($sub === '') {
            throw new RuntimeException('Missing sub in SSO userinfo payload.');
        }

        $email = isset($userInfo['email']) ? (string) $userInfo['email'] : null;
        $name = (string) ($userInfo['name'] ?? $email ?? $sub);
        $username = (string) ($userInfo['preferred_username'] ?? $email ?? $sub);

        $user = User::where('sso_sub', $sub)->first();

        if ($user === null && $email !== null && $email !== '') {
            $user = User::where('email', $email)->first();
        }

        if ($user === null) {
            $defaultTipe = TipeUser::query()->first();

            if ($defaultTipe === null) {
                DB::table('tipe_users')->insert([
                    'nama' => 'Default',
                    'deskripsi' => 'Auto-created for SSO migration',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $defaultTipe = TipeUser::query()->firstOrFail();
            }

            $defaultUnit = UnitKerja::query()->first();

            if ($defaultUnit === null) {
                DB::table('unit_kerjas')->insert([
                    'nama' => 'Default',
                    'deskripsi' => 'Auto-created for SSO migration',
                    'tipe' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $defaultUnit = UnitKerja::query()->firstOrFail();
            }

            $user = new User();
            $user->nama = $name;
            $user->email = $email ?? sprintf('%s@sso.local', Str::slug($sub));
            $user->username = $username;
            $user->password = Hash::make(Str::random(64));
            $user->image_path = '-';
            $user->api_token = '';
            $user->fcm_id = '';
            $user->tipe_user_id = $defaultTipe->id;
            $user->unit_kerja_id = $defaultUnit->id;
            $user->jenis_kelamin = (string) ($userInfo['gender'] ?? '-');
            $user->alamat = (string) ($userInfo['address'] ?? '-');
            $user->agama = '-';
            $user->tempat_lahir = '-';
            $user->tanggal_lahir = (string) ($userInfo['birthdate'] ?? '-');
            $user->no_hp = (string) ($userInfo['phone_number'] ?? '-');
        }

        $user->sso_sub = $sub;
        $user->nama = $name;
        $user->email = $email ?? $user->email;
        $user->sso_department = isset($userInfo['department']) ? (string) $userInfo['department'] : null;
        $user->sso_program = isset($userInfo['program']) ? (string) $userInfo['program'] : null;
        $user->sso_support_unit = isset($userInfo['support_unit']) ? (string) $userInfo['support_unit'] : null;
        $user->sso_synced_at = now();
        $user->save();

        return $user;
    }
}
