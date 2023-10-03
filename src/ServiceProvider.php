<?php


namespace StackTrace\Inertia;


use Illuminate\Support\ServiceProvider as Provider;
use Inertia\Inertia;
use StackTrace\Inertia\Facades\Notifications;

class ServiceProvider extends Provider
{
    public function register()
    {
        $this->app->singleton('inertia.notifications', fn () => new NotificationManager);
    }

    public function boot()
    {
        $this->app->booted(function () {
            Inertia::share('notifications', fn () => Notifications::all());
        });
    }
}
