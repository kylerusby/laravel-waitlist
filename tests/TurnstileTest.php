<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use KyleRusby\LaravelWaitlist\Services\TurnstileService;

beforeEach(function () {
    Config::set('waitlist.turnstile.enabled', true);
    Config::set('waitlist.turnstile.secret_key', 'test-secret-key');
});

it('can verify a valid turnstile token', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => true,
            'challenge_ts' => '2024-01-01T00:00:00Z',
            'hostname' => 'example.com',
        ], 200),
    ]);

    $service = new TurnstileService;
    $result = $service->verify('valid-token');

    expect($result)->toBeTrue();
});

it('returns false for invalid turnstile token', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ], 200),
    ]);

    $service = new TurnstileService;
    $result = $service->verify('invalid-token');

    expect($result)->toBeFalse();
});

it('includes remote IP when provided', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => true,
        ], 200),
    ]);

    $service = new TurnstileService;
    $service->verify('valid-token', '192.168.1.1');

    Http::assertSent(function ($request) {
        return $request->data()['remoteip'] === '192.168.1.1';
    });
});

it('includes idempotency key when provided', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => true,
        ], 200),
    ]);

    $service = new TurnstileService;
    $service->verify('valid-token', null, 'test-uuid-1234');

    Http::assertSent(function ($request) {
        return $request->data()['idempotency_key'] === 'test-uuid-1234';
    });
});

it('returns true when turnstile is disabled', function () {
    Config::set('waitlist.turnstile.enabled', false);

    $service = new TurnstileService;
    $result = $service->verify('any-token');

    expect($result)->toBeTrue();

    Http::assertNothingSent();
});

it('returns false when secret key is not configured', function () {
    Config::set('waitlist.turnstile.secret_key', '');

    $service = new TurnstileService;
    $result = $service->verify('any-token');

    expect($result)->toBeFalse();
});

it('handles API request failures gracefully', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response('Server Error', 500),
    ]);

    $service = new TurnstileService;
    $result = $service->verify('valid-token');

    expect($result)->toBeFalse();
});

it('handles exceptions gracefully', function () {
    Http::fake(function () {
        throw new \Exception('Network error');
    });

    $service = new TurnstileService;
    $result = $service->verify('valid-token');

    expect($result)->toBeFalse();
});

it('can get detailed verification result', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => true,
            'challenge_ts' => '2024-01-01T00:00:00Z',
            'hostname' => 'example.com',
        ], 200),
    ]);

    $service = new TurnstileService;
    $result = $service->getVerificationResult('valid-token');

    expect($result)
        ->toBeArray()
        ->toHaveKey('success', true)
        ->toHaveKey('challenge_ts')
        ->toHaveKey('hostname');
});

it('validates waitlist request with turnstile enabled', function () {
    Config::set('waitlist.turnstile.enabled', true);

    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => true,
        ], 200),
    ]);

    $response = $this->post(route('waitlist.store'), [
        'email' => 'test@example.com',
        'cf-turnstile-response' => 'valid-token',
    ]);

    $response->assertSessionHasNoErrors();
});

it('fails validation when turnstile token is missing', function () {
    Config::set('waitlist.turnstile.enabled', true);

    $response = $this->post(route('waitlist.store'), [
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasErrors('cf-turnstile-response');
});

it('fails validation when turnstile token is invalid', function () {
    Config::set('waitlist.turnstile.enabled', true);

    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ], 200),
    ]);

    $response = $this->post(route('waitlist.store'), [
        'email' => 'test@example.com',
        'cf-turnstile-response' => 'invalid-token',
    ]);

    $response->assertSessionHasErrors('cf-turnstile-response');
});

it('does not require turnstile when disabled', function () {
    Config::set('waitlist.turnstile.enabled', false);

    $response = $this->post(route('waitlist.store'), [
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasNoErrors();
});
