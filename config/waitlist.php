<?php

// config for KyleRusby/LaravelWaitlist
return [

    /*
    |--------------------------------------------------------------------------
    | Waitlist Routes Configuration
    |--------------------------------------------------------------------------
    |
    | Control whether the package routes are enabled and configure their paths.
    |
    */

    'enabled' => env('WAITLIST_ENABLED', true),

    'routes' => [
        'enabled' => true,
        'prefix' => '',
        'middleware' => ['web'],
        'paths' => [
            'index' => '/waitlist',
            'store' => '/waitlist',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Waitlist Page Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the appearance and content of your waitlist page.
    |
    */

    'headline' => 'Be the First to Experience Something Amazing',
    'subheadline' => 'Join our exclusive waitlist and get early access when we launch. No spam, just updates that matter.',
    'badge_text' => 'Limited Early Access',
    'button_text' => 'Join Waitlist',
    'success_message' => 'Thank you for joining! We\'ll be in touch soon.',
    'member_count' => 1234,

];
