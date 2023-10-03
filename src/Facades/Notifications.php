<?php


namespace StackTrace\Inertia\Facades;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \StackTrace\Inertia\NotificationStack stack(string $name = 'default')
 * @method static \StackTrace\Inertia\NotificationStack push(array|Arrayable $notification, bool $autoDismiss = true)
 *
 * @see \StackTrace\Inertia\NotificationManager
 */
class Notifications extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'inertia.notifications';
    }
}
