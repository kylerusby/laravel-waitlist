<?php

use Illuminate\Support\Facades\Route;
use KyleRusby\LaravelWaitlist\Http\Controllers\WaitlistController;

// Only register routes if enabled
if (config('waitlist.enabled', true) && config('waitlist.routes.enabled', true)) {
    $routeConfig = config('waitlist.routes', []);
    
    Route::middleware($routeConfig['middleware'] ?? ['web'])
        ->prefix($routeConfig['prefix'] ?? '')
        ->group(function () use ($routeConfig) {
            Route::get(
                $routeConfig['paths']['index'] ?? '/waitlist',
                [WaitlistController::class, 'index']
            )->name('waitlist.index');
            
            Route::post(
                $routeConfig['paths']['store'] ?? '/waitlist',
                [WaitlistController::class, 'store']
            )->name('waitlist.store');
        });
}
