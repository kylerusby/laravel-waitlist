<?php

namespace Kyle Rusby\LaravelWaitlist\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kyle Rusby\LaravelWaitlist\LaravelWaitlist
 */
class LaravelWaitlist extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Kyle Rusby\LaravelWaitlist\LaravelWaitlist::class;
    }
}
