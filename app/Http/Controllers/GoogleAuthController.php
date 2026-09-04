<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GoogleOAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    private GoogleOAuthService $googleService;

    public function __construct(GoogleOAuthService $googleService)
    {
        $this->googleService = $googleService;
    }

    public function redirect()
    {
        if (!config('services.google.client_id')) {
            return redirect()->route('login')->with('sukses', 'Google OAuth belum dikonfigurasi.');
        }

        return redirect($this->googleService->getAuthUrl());
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('login')->with('sukses', 'Login Google dibatalkan.');
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $accessToken = $this->googleService->getAccessToken($request->code);
            $userInfo = $this->googleService->getUserInfo($accessToken);

            $user = User::where('google_id', $userInfo['sub'])
                ->orWhere('email', $userInfo['email'])
                ->first();

            if (!$user) {
                $nameParts = explode(' ', $userInfo['name']);
                $user = User::create([
                    'name' => $userInfo['name'],
                    'email' => $userInfo['email'],
                    'google_id' => $userInfo['sub'],
                    'avatar' => $userInfo['picture'] ?? null,
                    'password' => bcrypt(Str::random(32)),
                    'role' => 'pembeli',
                    'kota' => 'Indonesia',
                ]);
            } else {
                $user->update([
                    'google_id' => $userInfo['sub'],
                    'avatar' => $userInfo['picture'] ?? $user->avatar,
                ]);
            }

            Auth::login($user);

            return match ($user->role) {
                'admin' => redirect()->route('dashboard.admin'),
                default => redirect()->route('dashboard.pembeli'),
            };

        } catch (\Throwable $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage());
            return redirect()->route('login')->with('sukses', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
