<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Concerns;

use Closure;

trait HasTitle
{
    protected string|Closure|null $title = null;

    public function title(string|Closure|null $title = null): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->evaluate($this->title);
        }

        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->evaluate($this->title);
    }
}
