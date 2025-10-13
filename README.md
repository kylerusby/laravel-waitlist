# Laravel Waitlist

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kylerusby/laravel-waitlist.svg?style=flat-square)](https://packagist.org/packages/kylerusby/laravel-waitlist)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kylerusby/laravel-waitlist/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kylerusby/laravel-waitlist/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kylerusby/laravel-waitlist/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kylerusby/laravel-waitlist/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kylerusby/laravel-waitlist.svg?style=flat-square)](https://packagist.org/packages/kylerusby/laravel-waitlist)

A beautiful, ready-to-use Laravel package for managing waitlists. Collect email addresses from interested users with a stunning, pre-built landing page styled with Tailwind CSS. Perfect for product launches, beta releases, or any project that needs to build anticipation.

## Features

- 🎨 **Beautiful Pre-built UI** - Modern, responsive waitlist page with Tailwind CSS
- 📧 **Email Collection** - Simple form with validation and unique email constraint
- ⚙️ **Highly Configurable** - Customize text, colors, and behavior via config
- 🛣️ **Flexible Routing** - Enable/disable routes or override paths and middleware
- 🎯 **Easy Integration** - Works out of the box with zero configuration
- 🔒 **Form Validation** - Built-in request validation with custom error messages
- 🎭 **Customizable Views** - Publish and modify the Blade template to match your brand

## Installation

### Via Composer

```bash
composer require kylerusby/laravel-waitlist
```

### Local Development

To use this package locally in your Laravel project:

1. Add the path repository to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/laravel-waitlist",
            "options": {
                "symlink": true
            }
        }
    ]
}
```

2. Require the package:

```bash
composer require kylerusby/laravel-waitlist @dev
```

### Publish and Run Migrations

```bash
php artisan vendor:publish --tag="laravel-waitlist-migrations"
php artisan migrate
```

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag="laravel-waitlist-config"
```

### Publish Views (Optional)

```bash
php artisan vendor:publish --tag="laravel-waitlist-views"
```

## Usage

Once installed, the package works immediately with zero configuration!

### Quick Start

Visit `/waitlist` in your Laravel application to see the pre-built waitlist page. Users can enter their email addresses, and they'll be saved to the database.

### Accessing Waitlist Data

```php
use KyleRusby\LaravelWaitlist\Models\Waitlist;

// Get all waitlist entries
$entries = Waitlist::all();

// Get recent entries
$recent = Waitlist::latest()->take(10)->get();

// Export emails
$emails = Waitlist::pluck('email')->toArray();

// Count total signups
$count = Waitlist::count();
```

### Custom Controller Usage

If you disable the default routes, you can use the controller in your own routes:

```php
use KyleRusby\LaravelWaitlist\Http\Controllers\WaitlistController;

Route::get('/join-us', [WaitlistController::class, 'index']);
Route::post('/join-us', [WaitlistController::class, 'store']);
```

## Configuration

After publishing the config file, you'll find it at `config/waitlist.php`. Here are the available options:

### Enable/Disable Waitlist

Control whether the waitlist functionality is enabled:

```php
// .env
WAITLIST_ENABLED=true

// Or in config/waitlist.php
'enabled' => env('WAITLIST_ENABLED', true),
```

### Route Configuration

Customize the routes or disable them entirely:

```php
'routes' => [
    'enabled' => true,              // Enable/disable package routes
    'prefix' => '',                 // Add a prefix (e.g., 'early-access')
    'middleware' => ['web'],        // Apply middleware
    'paths' => [
        'index' => '/waitlist',     // GET route path
        'store' => '/waitlist',     // POST route path
    ],
],
```

**Examples:**

Disable routes to use your own:
```php
'routes' => [
    'enabled' => false,
],
```

Add a prefix and custom path:
```php
'routes' => [
    'prefix' => 'early-access',
    'paths' => [
        'index' => '/join',
        'store' => '/join',
    ],
],
// Routes will be: /early-access/join
```

Apply guest middleware:
```php
'routes' => [
    'middleware' => ['web', 'guest'],
],
```

### Page Content

Customize the waitlist page text:

```php
'headline' => 'Be the First to Experience Something Amazing',
'subheadline' => 'Join our exclusive waitlist and get early access when we launch.',
'badge_text' => 'Limited Early Access',
'button_text' => 'Join Waitlist',
'success_message' => 'Thank you for joining! We\'ll be in touch soon.',
'member_count' => 1234,  // Displayed as social proof
```

### Complete Configuration Example

```php
<?php

return [
    'enabled' => env('WAITLIST_ENABLED', true),

    'routes' => [
        'enabled' => true,
        'prefix' => 'beta',
        'middleware' => ['web', 'throttle:60,1'],
        'paths' => [
            'index' => '/signup',
            'store' => '/signup',
        ],
    ],

    'headline' => 'Join the Beta Program',
    'subheadline' => 'Get exclusive early access to our new platform.',
    'badge_text' => '🚀 Beta Access',
    'button_text' => 'Get Early Access',
    'success_message' => 'Welcome to the beta! Check your email for next steps.',
    'member_count' => 500,
];
```

## Customization

### Customizing the View

Publish the views to customize the waitlist page:

```bash
php artisan vendor:publish --tag="laravel-waitlist-views"
```

The view will be published to `resources/views/vendor/laravel-waitlist/waitlist.blade.php`. You can then modify the HTML, styling, and layout to match your brand.

### Using Your Own View

If you've disabled the package routes, you can create your own controller and view:

```php
// routes/web.php
Route::get('/waitlist', function () {
    return view('my-custom-waitlist');
});

Route::post('/waitlist', function (Request $request) {
    $request->validate([
        'email' => 'required|email|unique:waitlist,email',
    ]);
    
    \KyleRusby\LaravelWaitlist\Models\Waitlist::create([
        'email' => $request->email,
    ]);
    
    return back()->with('success', 'Added to waitlist!');
});
```

### Database Model

The `Waitlist` model is a standard Eloquent model with the following attributes:

- `id` - Primary key
- `email` - Unique email address (string, 255)
- `created_at` - Timestamp
- `updated_at` - Timestamp

You can extend or customize it by creating your own model that extends the package's model:

```php
namespace App\Models;

use KyleRusby\LaravelWaitlist\Models\Waitlist as BaseWaitlist;

class Waitlist extends BaseWaitlist
{
    // Add custom methods or attributes
    public function notify()
    {
        // Send notification email
    }
}
```

## Advanced Usage

### Exporting Waitlist

Export all emails to a CSV:

```php
use KyleRusby\LaravelWaitlist\Models\Waitlist;
use Illuminate\Support\Facades\Response;

Route::get('/export-waitlist', function () {
    $waitlist = Waitlist::all(['email', 'created_at']);
    
    $csvData = $waitlist->map(function ($entry) {
        return [
            $entry->email,
            $entry->created_at->format('Y-m-d H:i:s'),
        ];
    });
    
    $headers = ['Email', 'Joined At'];
    array_unshift($csvData, $headers);
    
    // Generate CSV...
    
    return Response::download($filename);
});
```

### Email Notifications

Send notifications to new signups:

```php
use KyleRusby\LaravelWaitlist\Models\Waitlist;
use Illuminate\Support\Facades\Mail;

Waitlist::created(function ($waitlist) {
    Mail::to($waitlist->email)->send(new WelcomeToWaitlist());
});
```

### Adding Additional Fields

If you need to collect more than just email addresses, extend the migration:

```php
// In your published migration
Schema::create('waitlist', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();
    $table->string('name')->nullable();
    $table->string('company')->nullable();
    $table->timestamps();
});
```

Then update your form request validation and form fields accordingly.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kyle Rusby](https://github.com/kylerusby)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
