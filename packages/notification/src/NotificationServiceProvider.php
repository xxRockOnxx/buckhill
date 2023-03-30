<?php

namespace Lemuel\Notification;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;
use Lemuel\Notification\Listeners\SendOrderStatusToTeams;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(SendOrderStatusToTeams::class, function ($app) {
            return new SendOrderStatusToTeams(
                $app->make(Factory::class),
                config('notification.teams.webhook'),
                config('notification.teams.message_class', SimpleTeamsMessagePayload::class)
            );
        });
    }
}
