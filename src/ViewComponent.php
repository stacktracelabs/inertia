<?php


namespace StackTrace\Inertia;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class ViewComponent implements Arrayable
{
    public function __construct(
        protected string $name,
        protected array $props = []
    ) { }

    public function with(string|array $key, mixed $value = null): static
    {
        if (is_array($key)) {
            $this->props = array_merge($this->props, $key);
        } else {
            Arr::set($this->props, $key, $value);
        }

        return $this;
    }

    public function prop(string $name, mixed $default = null): mixed
    {
        return Arr::get($this->props, $name, $default);
    }

    public function getProps(): array
    {
        return $this->props;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'props' => (object) $this->props,
        ];
    }
}
