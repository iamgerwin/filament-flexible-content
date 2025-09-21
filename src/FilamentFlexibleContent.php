<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent;

use Illuminate\Support\Collection;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;
use IamGerwin\FilamentFlexibleContent\Layouts\Preset;

final class FilamentFlexibleContent
{
    private Collection $layouts;

    private Collection $presets;

    public function __construct()
    {
        $this->layouts = collect();
        $this->presets = collect();
    }

    public function registerLayout(Layout|string $layout): self
    {
        if (is_string($layout) && class_exists($layout)) {
            $layout = new $layout();
        }

        if ($layout instanceof Layout) {
            $this->layouts->put($layout->name(), $layout);
        }

        return $this;
    }

    public function registerLayouts(array $layouts): self
    {
        foreach ($layouts as $layout) {
            $this->registerLayout($layout);
        }

        return $this;
    }

    public function registerPreset(Preset|string $preset, ?string $name = null): self
    {
        if (is_string($preset) && class_exists($preset)) {
            $preset = new $preset();
        }

        if ($preset instanceof Preset) {
            $name = $name ?? class_basename($preset::class);
            $this->presets->put($name, $preset);
        }

        return $this;
    }

    public function registerPresets(array $presets): self
    {
        foreach ($presets as $name => $preset) {
            $this->registerPreset($preset, is_string($name) ? $name : null);
        }

        return $this;
    }

    public function getLayouts(): Collection
    {
        return $this->layouts;
    }

    public function getLayout(string $name): ?Layout
    {
        return $this->layouts->get($name);
    }

    public function hasLayout(string $name): bool
    {
        return $this->layouts->has($name);
    }

    public function getPresets(): Collection
    {
        return $this->presets;
    }

    public function getPreset(string $name): ?Preset
    {
        return $this->presets->get($name);
    }

    public function hasPreset(string $name): bool
    {
        return $this->presets->has($name);
    }

    public function clearLayouts(): self
    {
        $this->layouts = collect();

        return $this;
    }

    public function clearPresets(): self
    {
        $this->presets = collect();

        return $this;
    }

    public function clear(): self
    {
        return $this->clearLayouts()->clearPresets();
    }
}