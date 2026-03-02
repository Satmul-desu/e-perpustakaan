<?php

namespace App\Providers;

use App\Events\OrderPaidEvent;
use App\Listeners\MergeCartListener;
use App\Listeners\SendOrderPaidEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Auth Events
        \Illuminate\Auth\Events\Login::class => [
            MergeCartListener::class,
        ],
        
        // Order Events
        OrderPaidEvent::class => [
            SendOrderPaidEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

