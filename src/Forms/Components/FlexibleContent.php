<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Forms\Components;

use Closure;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use IamGerwin\FilamentFlexibleContent\Concerns\HasLayouts;
use IamGerwin\FilamentFlexibleContent\Contracts\FlexibleContentContract;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;
use IamGerwin\FilamentFlexibleContent\Layouts\Preset;
use Illuminate\Support\Collection;

final class FlexibleContent extends Builder implements FlexibleContentContract
{
    use HasLayouts;

    protected string $view = 'filament-flexible-content::forms.components.flexible-content';

    protected array|Closure $layouts = [];

    protected ?Preset $preset = null;

    protected bool|Closure $collapsible = true;

    protected bool|Closure $cloneable = true;

    protected int|Closure|null $minLayouts = null;

    protected int|Closure|null $maxLayouts = null;

    protected array|Closure $limitedToLayouts = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->blocks(function (): array {
            return $this->getLayoutBlocks();
        });

        $this->columnSpanFull();
        $this->collapsible();
        $this->cloneable();
        $this->reorderable();
        $this->blockNumbers(false);
    }

    public function layouts(array|Closure $layouts): static
    {
        $this->layouts = $layouts;

        return $this;
    }

    public function preset(Preset|string|null $preset): static
    {
        if (is_string($preset) && class_exists($preset)) {
            $preset = new $preset;
        }

        $this->preset = $preset;

        return $this;
    }

    public function minLayouts(int|Closure|null $minimum): static
    {
        $this->minLayouts = $minimum;
        $this->minItems($minimum);

        return $this;
    }

    public function maxLayouts(int|Closure|null $maximum): static
    {
        $this->maxLayouts = $maximum;
        $this->maxItems($maximum);

        return $this;
    }

    public function onlyLayouts(array|Closure $layouts): static
    {
        $this->limitedToLayouts = $layouts;

        return $this;
    }

    public function getLayouts(): Collection
    {
        $layouts = $this->evaluate($this->layouts);

        if ($this->preset) {
            $layouts = array_merge($this->preset->layouts(), $layouts);
        }

        return collect($layouts)
            ->filter(fn ($layout) => $layout instanceof Layout)
            ->when(
                $this->hasLimitedLayouts(),
                fn (Collection $layouts) => $layouts->filter(
                    fn (Layout $layout) => in_array($layout->name(), $this->evaluate($this->limitedToLayouts))
                )
            )
            ->mapWithKeys(fn (Layout $layout) => [$layout->name() => $layout]);
    }

    protected function getLayoutBlocks(): array
    {
        return $this->getLayouts()
            ->map(fn (Layout $layout) => $this->createBlockFromLayout($layout))
            ->toArray();
    }

    protected function createBlockFromLayout(Layout $layout): Block
    {
        return Block::make($layout->name())
            ->label($layout->title())
            ->icon($layout->icon())
            ->schema($layout->fields())
            ->columns($layout->columns())
            ->maxItems($layout->limit())
            ->visible($layout->isVisible());
    }

    public function hasLimitedLayouts(): bool
    {
        return ! empty($this->evaluate($this->limitedToLayouts));
    }

    public function getState(): array
    {
        $state = parent::getState();

        return collect($state)
            ->map(function (array $item) {
                $layout = $this->getLayouts()->get($item['type'] ?? '');

                if (! $layout) {
                    return $item;
                }

                return array_merge($item, [
                    'layout' => $layout->name(),
                    'key' => $item['data']['block_key'] ?? uniqid(),
                ]);
            })
            ->toArray();
    }

    public function fill(?array $state = null): static
    {
        if (is_array($state)) {
            $state = collect($state)
                ->map(function (array $item) {
                    return [
                        'type' => $item['layout'] ?? $item['type'] ?? '',
                        'data' => array_merge(
                            $item['data'] ?? [],
                            ['block_key' => $item['key'] ?? uniqid()]
                        ),
                    ];
                })
                ->toArray();
        }

        return parent::fill($state);
    }

    public function getRules(): array
    {
        $rules = parent::getRules();

        if ($this->minLayouts !== null) {
            $rules[] = "min:{$this->evaluate($this->minLayouts)}";
        }

        if ($this->maxLayouts !== null) {
            $rules[] = "max:{$this->evaluate($this->maxLayouts)}";
        }

        return $rules;
    }
}
