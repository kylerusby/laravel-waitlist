<?php

namespace Kyle Rusby\LaravelWaitlist;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Kyle Rusby\LaravelWaitlist\Commands\LaravelWaitlistCommand;

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
            ->hasMigration('create_laravel_waitlist_table')
            ->hasCommand(LaravelWaitlistCommand::class);
    }
}
