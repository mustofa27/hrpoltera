<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SsoService
{
    public function authorizationUrl(string $state): string
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.sso.client_id'),
            'redirect_uri' => config('services.sso.redirect_uri'),
            'scope' => config('services.sso.scope'),
            'state' => $state,
        ]);

        return rtrim((string) config('services.sso.base_url'), '/').'/oauth/authorize?'.$query;
    }

    public function exchangeAuthorizationCode(string $code): array
    {
        $response = Http::asForm()
            ->timeout(10)
            ->post(rtrim((string) config('services.sso.base_url'), '/').'/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('services.sso.redirect_uri'),
                'client_id' => config('services.sso.client_id'),
                'client_secret' => config('services.sso.client_secret'),
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Failed to exchange OAuth authorization code.');
        }

        return $response->json();
    }

    public function fetchUserInfo(string $accessToken): array
    {
        $baseUrl = rtrim((string) config('services.sso.base_url'), '/');

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout(10)
            ->get($baseUrl.'/oauth/userinfo');

        if (! $response->successful()) {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->timeout(10)
                ->post($baseUrl.'/oauth/userinfo');
        }

        if (! $response->successful()) {
            throw new RuntimeException('Failed to fetch userinfo from SSO provider.');
        }

        return $response->json();
    }

    public function signRawBody(string $rawBody): string
    {
        return hash_hmac('sha256', $rawBody, (string) config('services.sso.client_secret'));
    }

    public function signPayload(array $payload): array
    {
        $rawBody = json_encode($payload, JSON_UNESCAPED_SLASHES);

        return [
            'raw' => $rawBody,
            'signature' => $this->signRawBody($rawBody),
        ];
    }

    public function hasValidSignature(string $rawBody, ?string $signatureHeader): bool
    {
        if (! is_string($signatureHeader) || $signatureHeader === '') {
            return false;
        }

        $expectedHex = $this->signRawBody($rawBody);
        $expectedBase64 = base64_encode(hex2bin($expectedHex));

        return hash_equals($expectedHex, $signatureHeader)
            || hash_equals($expectedBase64, $signatureHeader)
            || hash_equals('sha256='.$expectedHex, $signatureHeader)
            || hash_equals('sha256='.$expectedBase64, $signatureHeader);
    }

    public function hasValidTimestamp(int $timestamp): bool
    {
        $skew = (int) config('services.sso.allowed_timestamp_skew', 300);

        return abs(now()->timestamp - $timestamp) <= $skew;
    }
}
