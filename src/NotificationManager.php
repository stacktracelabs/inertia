<?php


namespace StackTrace\Inertia;


use Illuminate\Support\Arr;

class NotificationManager
{
    /**
     * List of notification stacks.
     */
    protected array $stacks = [];

    /**
     * Retrieve notification stack with given name.
     */
    public function stack(string $stack = 'default'): NotificationStack
    {
        if (! Arr::has($this->stacks, $stack)) {
            $this->stacks[$stack] = $this->createStack($stack);
        }

        return $this->stacks[$stack];
    }

    /**
     * Create new notification stack.
     */
    protected function createStack(string $name): NotificationStack
    {
        return new NotificationStack();
    }

    /**
     * Retrieve all notifications
     */
    public function all(): array
    {
        return collect($this->stacks)->map(fn (NotificationStack $stack) => $stack->all())->all();
    }

    /**
     * Dynamically call the default stack instance.
     */
    public function __call(string $method, array $parameters)
    {
        return $this->stack()->$method(...$parameters);
    }
}
