<?php

namespace KyleRusby\LaravelWaitlist\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    /**
     * The Cloudflare Turnstile API endpoint.
     */
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Verify the Turnstile token.
     *
     * @param  string  $token  The Turnstile response token
     * @param  string|null  $remoteIp  The visitor's IP address (optional)
     * @param  string|null  $idempotencyKey  UUID for retry protection (optional)
     */
    public function verify(string $token, ?string $remoteIp = null, ?string $idempotencyKey = null): bool
    {
        // If Turnstile is not enabled, skip verification
        if (! config('waitlist.turnstile.enabled', false)) {
            return true;
        }

        $secret = config('waitlist.turnstile.secret_key');

        // If no secret key is configured, log error and fail
        if (empty($secret)) {
            Log::error('Cloudflare Turnstile secret key is not configured');

            return false;
        }

        // Prepare request data
        $data = [
            'secret' => $secret,
            'response' => $token,
        ];

        // Add optional parameters if provided
        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        if ($idempotencyKey) {
            $data['idempotency_key'] = $idempotencyKey;
        }

        try {
            // Make the verification request
            $response = Http::asForm()->post(self::VERIFY_URL, $data);

            // Check if request was successful
            if (! $response->successful()) {
                Log::error('Cloudflare Turnstile API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            $result = $response->json();

            // Log verification result for debugging
            if (config('app.debug')) {
                Log::debug('Cloudflare Turnstile verification result', [
                    'success' => $result['success'] ?? false,
                    'error-codes' => $result['error-codes'] ?? [],
                ]);
            }

            // Return the success status
            return $result['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Cloudflare Turnstile verification exception', [
                'message' => $e->getMessage(),
                'token' => substr($token, 0, 20).'...',
            ]);

            return false;
        }
    }

    /**
     * Get the verification result with detailed error information.
     *
     * @param  string  $token  The Turnstile response token
     * @param  string|null  $remoteIp  The visitor's IP address (optional)
     * @param  string|null  $idempotencyKey  UUID for retry protection (optional)
     */
    public function getVerificationResult(string $token, ?string $remoteIp = null, ?string $idempotencyKey = null): array
    {
        // If Turnstile is not enabled, return success
        if (! config('waitlist.turnstile.enabled', false)) {
            return ['success' => true];
        }

        $secret = config('waitlist.turnstile.secret_key');

        if (empty($secret)) {
            return [
                'success' => false,
                'error-codes' => ['missing-secret-key'],
            ];
        }

        $data = [
            'secret' => $secret,
            'response' => $token,
        ];

        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        if ($idempotencyKey) {
            $data['idempotency_key'] = $idempotencyKey;
        }

        try {
            $response = Http::asForm()->post(self::VERIFY_URL, $data);

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'error-codes' => ['api-request-failed'],
                ];
            }

            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error-codes' => ['exception'],
                'exception' => $e->getMessage(),
            ];
        }
    }
}
