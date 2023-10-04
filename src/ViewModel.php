<?php


namespace StackTrace\Inertia;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function collect;

abstract class ViewModel implements Arrayable, \JsonSerializable
{
    /**
     * Format data to view.
     */
    public abstract function toView(): array;

    public function jsonSerialize(): mixed
    {
        return $this->prepareValue($this->toView(), 'snake');
    }

    public function toArray()
    {
        return $this->prepareValue($this->toView(), 'camel');
    }

    protected function formatKey(string $key, $case = 'snake'): string
    {
        if ($case == 'snake') {
            return Str::snake($key);
        } else if ($case == 'camel') {
            return Str::camel($key);
        }

        throw new \RuntimeException("Either [snake] or [camel] are supported.");
    }

    protected function prepareValue(mixed $value, string $case): mixed
    {
        if (is_array($value)) {
            // Convert associative array
            if (Arr::isAssoc($value)) {
                return collect($value)
                    ->mapWithKeys(fn ($val, $key) => [$this->formatKey($key, $case) => $this->prepareValue($val, $case)])
                    ->all();
            } else {
                // Convert regular array
                return collect($value)->map(fn ($val) => $this->prepareValue($val, $case))->all();
            }
        } else if ($value instanceof Collection) {
            // Convert collection
            return $value->map(fn ($val) => $this->prepareValue($val, $case))->all();
        } else if ($value instanceof ViewModel) {
            // Convert another view model
            return $this->prepareValue($value->toView(), $case);
        }

        return $value;
    }

    public static function make(): static
    {
        return new static(...func_get_args());
    }

    public static function collect(array|Collection $items): Collection
    {
        return Collection::wrap($items)->mapInto(static::class)->values();
    }
}
