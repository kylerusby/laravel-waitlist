<?php

namespace KyleRusby\LaravelWaitlist\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KyleRusby\LaravelWaitlist\LaravelWaitlist
 */
class LaravelWaitlist extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \KyleRusby\LaravelWaitlist\LaravelWaitlist::class;
    }
}
