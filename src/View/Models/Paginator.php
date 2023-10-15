<?php


namespace StackTrace\Inertia\View\Models;


use Closure;
use Illuminate\Pagination\LengthAwarePaginator;
use StackTrace\Inertia\ViewModel;

class Paginator extends ViewModel
{
    public function __construct(
        protected LengthAwarePaginator $paginator,
        protected ?Closure $through = null,
    ) { }

    /**
     * Transform each item in the slice of items using a callback.
     */
    public function through(Closure $through): static
    {
        $this->through = $through;

        return $this;
    }

    public function toView(): array
    {
        return ($this->through ? $this->paginator->through($this->through) : $this->paginator)
            ->toArray();
    }
}
