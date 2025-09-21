<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Contracts;

use Closure;
use Illuminate\Support\Collection;
use IamGerwin\FilamentFlexibleContent\Layouts\Preset;

interface FlexibleContentContract
{
    public function layouts(array|Closure $layouts): static;

    public function preset(Preset|string|null $preset): static;

    public function minLayouts(int|Closure|null $minimum): static;

    public function maxLayouts(int|Closure|null $maximum): static;

    public function onlyLayouts(array|Closure $layouts): static;

    public function getLayouts(): Collection;

    public function hasLimitedLayouts(): bool;
}