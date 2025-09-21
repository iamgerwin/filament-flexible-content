<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Layouts;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Str;
use IamGerwin\FilamentFlexibleContent\Concerns\HasName;
use IamGerwin\FilamentFlexibleContent\Concerns\HasTitle;
use IamGerwin\FilamentFlexibleContent\Contracts\LayoutContract;

abstract class Layout implements LayoutContract
{
    use HasName;
    use HasTitle;

    protected string|Closure|null $icon = null;

    protected array|Closure $fields = [];

    protected int|Closure|null $limit = null;

    protected int|Closure $columns = 2;

    protected bool|Closure $visible = true;

    protected array $attributes = [];

    public function __construct()
    {
        $this->setUp();
    }

    public static function make(): static
    {
        return new static();
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

    public function isVisible(): bool
    {
        return $this->evaluate($this->visible);
    }

    public function isHidden(): bool
    {
        return ! $this->isVisible();
    }

    public function icon(): ?string
    {
        return $this->getIcon();
    }

    public function fields(): array
    {
        return $this->getFields();
    }

    public function limit(): ?int
    {
        return $this->getLimit();
    }

    public function columns(): int
    {
        return $this->getColumns();
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
            'icon' => $this->icon(),
            'fields' => $this->fields(),
            'limit' => $this->limit(),
            'columns' => $this->columns(),
            'visible' => $this->isVisible(),
            'attributes' => $this->attributes,
        ];
    }

    protected function evaluate(mixed $value): mixed
    {
        if ($value instanceof Closure) {
            return $value($this);
        }

        return $value;
    }
}