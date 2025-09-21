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

    protected array|Closure $layouts = [];

    protected ?Preset $preset = null;

    protected bool|Closure $collapsible = true;

    protected bool|Closure $cloneable = true;

    protected int|Closure|null $minLayouts = null;

    protected int|Closure|null $maxLayouts = null;

    protected array|Closure $limitedToLayouts = [];

    protected array $dependencies = [];

    protected Closure|bool|null $dependsOnClosure = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->blocks(function (): array {
            return $this->getLayoutBlocks();
        });

        $this->columnSpanFull();
        $this->collapsible();
        $this->configureCloneable();
        $this->configureAddable();
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

        // Reconfigure cloneable and addable to respect the maximum
        $this->configureCloneable();
        $this->configureAddable();

        return $this;
    }

    public function onlyLayouts(array|Closure $layouts): static
    {
        $this->limitedToLayouts = $layouts;

        return $this;
    }

    public function dependsOn(string|array $fields, Closure|bool|null $condition = null): static
    {
        if (is_string($fields)) {
            $fields = [$fields];
        }

        $this->dependencies = $fields;
        $this->dependsOnClosure = $condition;

        // Make the component reactive to the dependent fields
        $this->reactive();

        // Add live behavior to ensure real-time updates
        $this->live();

        // Set up visibility based on the condition
        $this->visible(function ($get) {
            if ($this->dependsOnClosure === null) {
                return true;
            }

            if (is_bool($this->dependsOnClosure)) {
                return $this->dependsOnClosure;
            }

            return ($this->dependsOnClosure)($get);
        });

        // Also make dependent fields reactive if they exist in the same form
        $this->afterStateHydrated(function ($component, $state) use ($fields) {
            $form = $component->getContainer();

            foreach ($fields as $field) {
                try {
                    $dependentComponent = $form->getComponent($field);
                    if ($dependentComponent && method_exists($dependentComponent, 'live')) {
                        $dependentComponent->live();
                    }
                } catch (\Exception $e) {
                    // Field might not exist in the same container, ignore
                }
            }
        });

        return $this;
    }

    protected function configureCloneable(): void
    {
        // If there's a max limit, make cloneable conditional
        if ($this->maxLayouts !== null) {
            $this->cloneable(function ($component, $state) {
                $maxLayouts = $this->evaluate($this->maxLayouts);

                if ($maxLayouts === null) {
                    return true;
                }

                // Count current items
                $currentCount = is_array($state) ? count($state) : 0;

                // Disable cloning if we've reached the maximum
                return $currentCount < $maxLayouts;
            });
        } else {
            // No limit, enable cloning
            $this->cloneable();
        }
    }

    protected function configureAddable(): void
    {
        // If there's a max limit, make addable conditional
        if ($this->maxLayouts !== null) {
            $this->addable(function ($component, $state) {
                $maxLayouts = $this->evaluate($this->maxLayouts);

                if ($maxLayouts === null) {
                    return true;
                }

                // Count current items
                $currentCount = is_array($state) ? count($state) : 0;

                // Disable adding if we've reached the maximum
                return $currentCount < $maxLayouts;
            });
        } else {
            // No limit, enable adding
            $this->addable();
        }
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
            ->icon($layout->getIcon())
            ->schema($layout->getFields())
            ->columns($layout->getColumns())
            ->maxItems($layout->getLimit())
            ->visible(fn ($get) => $layout->isVisible($get));
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
}
