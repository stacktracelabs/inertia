<?php


namespace StackTrace\Inertia;


use Illuminate\Contracts\Support\Arrayable;
use StackTrace\Inertia\Facades\Notifications;

class Notification implements Arrayable
{
    public function __construct(
        protected string $type,
        protected string $title,
        protected ?string $content = null,
        protected array $extra = []
    ) { }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'content' => $this->content,
            ...$this->extra,
        ];
    }

    /**
     * Push the notification into stack.
     */
    public function push(string $stack = 'default', bool $autoDismiss = true): void
    {
        Notifications::stack($stack)->push($this, $autoDismiss);
    }

    /**
     * Create a positive notification.
     */
    public static function positive(string $title, ?string $content = null, array $extra = []): static
    {
        return new static('positive', $title, $content, $extra);
    }

    /**
     * Create a negative notification.
     */
    public static function negative(string $title, ?string $content = null, array $extra = []): static
    {
        return new static('negative', $title, $content, $extra);
    }

    /**
     * Create a neutral notification.
     */
    public static function neutral(string $title, ?string $content = null, array $extra = []): static
    {
        return new static('neutral', $title, $content, $extra);
    }
}
