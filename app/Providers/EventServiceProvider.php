<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\ImportFailed::class => [
            \App\Listeners\SendImportFailureEmail::class,
        ],
    ];
}
