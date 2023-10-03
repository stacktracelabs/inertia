<?php


namespace StackTrace\Inertia;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class NotificationStack
{
    /**
     * Notifications in the stack.
     */
    protected array $notifications = [];

    /**
     * Push a notification to the stack.
     */
    public function push(array|Arrayable $notification, bool $autoDismiss = true): static
    {
        $this->notifications[] = [
            'id' => Str::random(),
            'value' => $notification instanceof Arrayable ? $notification->toArray() : $notification,
            'timestamp' => now()->timestamp,
            'autoDismiss' => $autoDismiss,
        ];

        return $this;
    }

    /**
     * Retrieve all notifications in the stack.
     */
    public function all(): array
    {
        return $this->notifications;
    }
}
