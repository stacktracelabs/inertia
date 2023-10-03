<?php


namespace StackTrace\Inertia\View\Models;


use StackTrace\Inertia\ViewModel;

class ArrayViewModel extends ViewModel
{
    public function __construct(
        protected array $value
    ) {}

    public function toView(): array
    {
        return $this->value;
    }
}
