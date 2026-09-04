<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleOAuthService
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUrl;

    public function __construct()
    {
        $this->clientId = config('services.google.client_id');
        $this->clientSecret = config('services.google.client_secret');
        $this->redirectUrl = config('services.google.redirect_url');
    }

    public function getAuthUrl(): string
    {
        $params = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;
    }

    public function getAccessToken(string $code): string
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUrl,
            'grant_type' => 'authorization_code',
        ]);

        if ($response->failed()) {
            Log::error('Google OAuth token error', $response->json());
            throw new RequestException('Failed to get access token from Google');
        }

        return $response->json('access_token');
    }

    public function getUserInfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if ($response->failed()) {
            Log::error('Google userinfo error', $response->json());
            throw new RequestException('Failed to get user info from Google');
        }

        return $response->json();
    }
}
