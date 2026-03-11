<?php

namespace App\Providers;

use App\Events\OrderPaidEvent;
use App\Listeners\MergeCartListener;
use App\Listeners\SendOrderPaidEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            MergeCartListener::class,
        ],
        OrderPaidEvent::class => [
            SendOrderPaidEmail::class,
        ],
    ];

    public function boot(): void {}

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
