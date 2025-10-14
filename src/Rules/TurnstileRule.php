<?php

namespace KyleRusby\LaravelWaitlist\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use KyleRusby\LaravelWaitlist\Services\TurnstileService;

class TurnstileRule implements ValidationRule
{
    /**
     * The Turnstile service instance.
     *
     * @var TurnstileService
     */
    protected TurnstileService $turnstile;

    /**
     * The visitor's IP address.
     *
     * @var string|null
     */
    protected ?string $remoteIp;

    /**
     * Create a new rule instance.
     *
     * @param  string|null  $remoteIp  The visitor's IP address
     */
    public function __construct(?string $remoteIp = null)
    {
        $this->turnstile = new TurnstileService();
        $this->remoteIp = $remoteIp;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if Turnstile is not enabled
        if (!config('waitlist.turnstile.enabled', false)) {
            return;
        }

        // Fail if token is missing
        if (empty($value)) {
            $fail('Please complete the security challenge.');
            return;
        }

        // Verify the token with Cloudflare
        $isValid = $this->turnstile->verify($value, $this->remoteIp);

        if (!$isValid) {
            $fail('Security verification failed. Please try again.');
        }
    }
}

