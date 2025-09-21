<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Concerns;

use Closure;

trait HasName
{
    protected string|Closure|null $name = null;

    public function name(string|Closure|null $name = null): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->evaluate($this->name);
        }

        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->evaluate($this->name);
    }
}