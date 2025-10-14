<?php

namespace KyleRusby\LaravelWaitlist\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use KyleRusby\LaravelWaitlist\LaravelWaitlistServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'KyleRusby\\LaravelWaitlist\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelWaitlistServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        // Set an application key to satisfy encryption requirements during tests
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        config()->set('waitlist.enabled', true);
        config()->set('waitlist.routes.enabled', true);

        $migration = include __DIR__.'/../database/migrations/create_waitlist_table.php.stub';
        $migration->up();
    }
}
