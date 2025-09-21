<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent registerLayout(\IamGerwin\FilamentFlexibleContent\Layouts\Layout|string $layout)
 * @method static \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent registerLayouts(array $layouts)
 * @method static \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent registerPreset(\IamGerwin\FilamentFlexibleContent\Layouts\Preset|string $preset, ?string $name = null)
 * @method static \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent registerPresets(array $presets)
 * @method static \Illuminate\Support\Collection getLayouts()
 * @method static \IamGerwin\FilamentFlexibleContent\Layouts\Layout|null getLayout(string $name)
 * @method static bool hasLayout(string $name)
 * @method static \Illuminate\Support\Collection getPresets()
 * @method static \IamGerwin\FilamentFlexibleContent\Layouts\Preset|null getPreset(string $name)
 * @method static bool hasPreset(string $name)
 * @method static \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent clearLayouts()
 * @method static \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent clearPresets()
 * @method static \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent clear()
 *
 * @see \IamGerwin\FilamentFlexibleContent\FilamentFlexibleContent
 */
final class FilamentFlexibleContent extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-flexible-content';
    }
}