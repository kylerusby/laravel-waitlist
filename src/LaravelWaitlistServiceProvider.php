<?php

namespace KyleRusby\LaravelWaitlist;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use KyleRusby\LaravelWaitlist\Commands\LaravelWaitlistCommand;

class LaravelWaitlistServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-waitlist')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_waitlist_table')
            ->hasCommand(LaravelWaitlistCommand::class);
    }

    public function packageBooted(): void
    {
        // Only register routes if enabled in config
        if (config('waitlist.enabled', true) && config('waitlist.routes.enabled', true)) {
            $this->registerRoutes();
        }
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
