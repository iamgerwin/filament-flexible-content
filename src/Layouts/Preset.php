<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Layouts;

use IamGerwin\FilamentFlexibleContent\Contracts\PresetContract;

abstract class Preset implements PresetContract
{
    protected array $layouts = [];

    abstract public function register(): void;

    public function __construct()
    {
        $this->register();
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function make(): static
    {
        return new static();
    }

    public function addLayout(Layout $layout): static
    {
        $this->layouts[$layout->name()] = $layout;

        return $this;
    }

    public function addLayouts(array $layouts): static
    {
        foreach ($layouts as $layout) {
            if ($layout instanceof Layout) {
                $this->addLayout($layout);
            }
        }

        return $this;
    }

    public function removeLayout(string $name): static
    {
        unset($this->layouts[$name]);

        return $this;
    }

    public function layouts(): array
    {
        return $this->layouts;
    }

    public function hasLayout(string $name): bool
    {
        return isset($this->layouts[$name]);
    }

    public function getLayout(string $name): ?Layout
    {
        return $this->layouts[$name] ?? null;
    }

    public function count(): int
    {
        return count($this->layouts);
    }

    public function isEmpty(): bool
    {
        return empty($this->layouts);
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function toArray(): array
    {
        return array_map(
            fn (Layout $layout) => $layout->toArray(),
            $this->layouts
        );
    }
}
