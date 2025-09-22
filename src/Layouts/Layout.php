<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Layouts;

use Closure;
use IamGerwin\FilamentFlexibleContent\Concerns\EvaluatesClosures;
use IamGerwin\FilamentFlexibleContent\Concerns\HasName;
use IamGerwin\FilamentFlexibleContent\Concerns\HasTitle;
use IamGerwin\FilamentFlexibleContent\Contracts\LayoutContract;
use Illuminate\Support\Str;

abstract class Layout implements LayoutContract
{
    use EvaluatesClosures;
    use HasName;
    use HasTitle;

    protected string|Closure|null $icon = null;

    protected array|Closure $fields = [];

    protected int|Closure|null $limit = null;

    protected int|Closure $columns = 2;

    protected bool|Closure $visible = true;

    protected array $dependencies = [];

    protected Closure|bool|null $dependsOnClosure = null;

    protected array $attributes = [];

    public function __construct()
    {
        $this->setUp();
    }

    public static function make(): static
    {
        return new static; // @phpstan-ignore-line
    }

    protected function setUp(): void
    {
        if (! $this->name) {
            $this->name = Str::of(class_basename(static::class))
                ->kebab()
                ->replace('-layout', '')
                ->toString();
        }

        if (! $this->title) {
            $this->title = Str::of($this->name)
                ->replace(['-', '_'], ' ')
                ->title()
                ->toString();
        }
    }

    public function icon(string|Closure|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function fields(array|Closure $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function limit(int|Closure|null $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function columns(int|Closure $columns = 2): static
    {
        $this->columns = $columns;

        return $this;
    }

    public function visible(bool|Closure $condition = true): static
    {
        $this->visible = $condition;

        return $this;
    }

    public function hidden(bool|Closure $condition = true): static
    {
        $this->visible = ! $condition;

        return $this;
    }

    public function dependsOn(string|array $fields, Closure|bool|null $condition = null): static
    {
        if (is_string($fields)) {
            $fields = [$fields];
        }

        $this->dependencies = $fields;
        $this->dependsOnClosure = $condition;

        // Combine the dependency condition with the existing visibility
        $originalVisible = $this->visible;

        $this->visible = function ($get) use ($originalVisible) {
            // First check the original visibility condition
            $isOriginallyVisible = is_bool($originalVisible) ? $originalVisible : $originalVisible($get);

            if (! $isOriginallyVisible) {
                return false;
            }

            // Then check the dependency condition
            if ($this->dependsOnClosure === null) {
                return true;
            }

            if (is_bool($this->dependsOnClosure)) {
                return $this->dependsOnClosure;
            }

            return ($this->dependsOnClosure)($get);
        };

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function getFields(): array
    {
        return $this->evaluate($this->fields);
    }

    public function getLimit(): ?int
    {
        return $this->evaluate($this->limit);
    }

    public function getColumns(): int
    {
        return $this->evaluate($this->columns);
    }

    public function isVisible($get = null): bool
    {
        if ($get !== null && is_callable($this->visible)) {
            return ($this->visible)($get);
        }

        return $this->evaluate($this->visible);
    }

    public function isHidden(): bool
    {
        return ! $this->isVisible();
    }

    public function with(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name(),
            'title' => $this->title(),
            'icon' => $this->getIcon(),
            'fields' => $this->getFields(),
            'limit' => $this->getLimit(),
            'columns' => $this->getColumns(),
            'visible' => $this->isVisible(),
            'dependencies' => $this->dependencies,
            'attributes' => $this->attributes,
        ];
    }
}
