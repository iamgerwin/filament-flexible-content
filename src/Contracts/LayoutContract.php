<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Contracts;

use Closure;

interface LayoutContract
{
    public static function make(): static;

    public function name(string|Closure|null $name = null): string|static|null;

    public function title(string|Closure|null $title = null): string|static|null;

    public function icon(string|Closure|null $icon): static;

    public function fields(array|Closure $fields): static;

    public function limit(int|Closure|null $limit): static;

    public function columns(int|Closure $columns = 2): static;

    public function visible(bool|Closure $condition = true): static;

    public function hidden(bool|Closure $condition = true): static;

    public function getIcon(): ?string;

    public function getFields(): array;

    public function getLimit(): ?int;

    public function getColumns(): int;

    public function isVisible(): bool;

    public function isHidden(): bool;

    public function toArray(): array;
}