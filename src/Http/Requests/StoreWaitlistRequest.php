<?php

namespace KyleRusby\LaravelWaitlist\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use KyleRusby\LaravelWaitlist\Rules\TurnstileRule;

class StoreWaitlistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => [
                'required',
                'email',
            ],
        ];

        // Add Turnstile validation if enabled
        if (config('waitlist.turnstile.enabled', false)) {
            $rules['cf-turnstile-response'] = [
                'required',
                new TurnstileRule($this->ip()),
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Please provide an email address.',
            'email.email' => 'Please provide a valid email address.',
            'cf-turnstile-response.required' => 'Please complete the security challenge.',
        ];
    }
}
