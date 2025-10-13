<?php

namespace Kyle Rusby\LaravelWaitlist\Commands;

use Illuminate\Console\Command;

class LaravelWaitlistCommand extends Command
{
    public $signature = 'laravel-waitlist';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
