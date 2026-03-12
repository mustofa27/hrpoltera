<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SsoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SsoBackchannelLogoutController extends Controller
{
    public function __invoke(Request $request, SsoService $ssoService): JsonResponse
    {
        $rawBody = (string) $request->getContent();
        $signature = $request->header('X-SSO-Signature');

        if (! $ssoService->hasValidSignature($rawBody, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = json_decode($rawBody, true);

        if (! is_array($payload) || ! isset($payload['timestamp']) || ! is_numeric($payload['timestamp'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        if (! $ssoService->hasValidTimestamp((int) $payload['timestamp'])) {
            return response()->json(['message' => 'Expired timestamp'], 401);
        }

        if (isset($payload['sid']) && is_string($payload['sid']) && $payload['sid'] !== '') {
            DB::table(config('session.table', 'sessions'))->where('id', $payload['sid'])->delete();
        }

        $sub = isset($payload['sub']) ? (string) $payload['sub'] : '';

        if ($sub !== '') {
            $user = User::where('sso_sub', $sub)->first();

            if ($user !== null) {
                DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
