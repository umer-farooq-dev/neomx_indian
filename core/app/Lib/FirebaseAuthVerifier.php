<?php

namespace App\Lib;

use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FirebaseAuthVerifier
{
    protected static function googlePublicKeys()
    {
        return Cache::remember('firebase_auth_public_keys', 3600, function () {
            $response = Http::get('https://www.googleapis.com/service_accounts/v1/jwk/securetoken@system.gserviceaccount.com');
            if (!$response->successful()) {
                throw new Exception('Could not fetch Firebase public keys');
            }
            return $response->json();
        });
    }

    /**
     * Verify a Firebase Auth ID token and return its decoded payload.
     * Throws an Exception with a user-safe message on any failure.
     */
    public static function verify($idToken)
    {
        $firebaseConfig = gs('firebase_config');
        $projectId = $firebaseConfig->projectId ?? null;

        if (!$projectId) {
            throw new Exception('Firebase is not configured on this site yet');
        }

        $keySet = JWK::parseKeySet(self::googlePublicKeys());
        $payload = JWT::decode($idToken, $keySet);

        if ($payload->aud !== $projectId) {
            throw new Exception('Token does not belong to this project');
        }
        if ($payload->iss !== 'https://securetoken.google.com/' . $projectId) {
            throw new Exception('Invalid token issuer');
        }
        if (empty($payload->phone_number)) {
            throw new Exception('Token does not contain a verified phone number');
        }

        return $payload;
    }
}
