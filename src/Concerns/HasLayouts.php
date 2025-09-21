<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Concerns;

use Closure;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;
use Illuminate\Support\Collection;

trait HasLayouts
{
    protected array|Closure $registeredLayouts = [];

    public function registerLayout(Layout|string $layout): static
    {
        if (is_string($layout) && class_exists($layout)) {
            $layout = new $layout;
        }

        if ($layout instanceof Layout) {
            $layouts = $this->evaluate($this->registeredLayouts);
            $layouts[$layout->name()] = $layout;
            $this->registeredLayouts = $layouts;
        }

        return $this;
    }

    public function registerLayouts(array $layouts): static
    {
        foreach ($layouts as $layout) {
            $this->registerLayout($layout);
        }

        return $this;
    }

    public function unregisterLayout(string $name): static
    {
        $layouts = $this->evaluate($this->registeredLayouts);
        unset($layouts[$name]);
        $this->registeredLayouts = $layouts;

        return $this;
    }

    public function getRegisteredLayouts(): Collection
    {
        return collect($this->evaluate($this->registeredLayouts));
    }

    public function hasRegisteredLayout(string $name): bool
    {
        return $this->getRegisteredLayouts()->has($name);
    }

    public function getRegisteredLayout(string $name): ?Layout
    {
        return $this->getRegisteredLayouts()->get($name);
    }
}
