<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Contracts;

use IamGerwin\FilamentFlexibleContent\Layouts\Layout;

interface PresetContract
{
    public function register(): void;

    public static function make(): static;

    public function addLayout(Layout $layout): static;

    public function addLayouts(array $layouts): static;

    public function removeLayout(string $name): static;

    public function layouts(): array;

    public function hasLayout(string $name): bool;

    public function getLayout(string $name): ?Layout;

    public function count(): int;

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function toArray(): array;
}