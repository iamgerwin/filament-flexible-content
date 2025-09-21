# Filament Flexible Content

[![Latest Version on Packagist](https://img.shields.io/packagist/v/iamgerwin/filament-flexible-content.svg?style=flat-square)](https://packagist.org/packages/iamgerwin/filament-flexible-content)
[![Total Downloads](https://img.shields.io/packagist/dt/iamgerwin/filament-flexible-content.svg?style=flat-square)](https://packagist.org/packages/iamgerwin/filament-flexible-content)
![PHP Version](https://img.shields.io/packagist/php-v/iamgerwin/filament-flexible-content?style=flat-square)
![Filament Version](https://img.shields.io/badge/Filament-v4.0-blue?style=flat-square)

Flexible Content & Repeater Fields for Laravel Filament v4. Built with PHP 8.2+ features for maximum performance and type safety.

## Features

- 🎨 **Flexible Layout System** - Create custom content layouts with ease
- 🔧 **Built for Filament v4** - Seamlessly integrates with Filament's form builder
- 🚀 **PHP 8.2+ Optimized** - Leverages modern PHP features for performance
- 📦 **Preset Support** - Bundle layouts into reusable presets
- 🎯 **Type-Safe** - Full type declarations and strict typing throughout
- 🧩 **Extensible** - Easy to extend with custom layouts and functionality
- 💾 **Cast Support** - Eloquent cast for seamless database integration
- 🛠️ **Artisan Commands** - Quickly scaffold new layouts
- 🧪 **Fully Tested** - Comprehensive test suite using Pest

## Requirements

- PHP ^8.2
- Laravel ^11.0 or ^12.0
- Filament ^4.0

## Installation

You can install the package via composer:

```bash
composer require iamgerwin/filament-flexible-content
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-flexible-content-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-flexible-content-config"
```

This is the contents of the published config file:

```php
return [
    'layouts_directory' => app_path('Filament/Flexible/Layouts'),
    'presets_directory' => app_path('Filament/Flexible/Presets'),
    'auto_register_layouts' => true,
    'auto_register_presets' => true,
    'cache' => [
        'enabled' => env('FLEXIBLE_CONTENT_CACHE', true),
        'key' => 'filament-flexible-content',
        'ttl' => 3600,
    ],
    'defaults' => [
        'collapsible' => true,
        'cloneable' => true,
        'reorderable' => true,
        'columns' => 2,
    ],
];
```

## Usage

### Basic Usage

Add the flexible content field to your Filament resource:

```php
use IamGerwin\FilamentFlexibleContent\Forms\Components\FlexibleContent;
use App\Filament\Flexible\Layouts\HeroLayout;
use App\Filament\Flexible\Layouts\ContentLayout;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            FlexibleContent::make('content')
                ->layouts([
                    HeroLayout::make(),
                    ContentLayout::make(),
                ])
        ]);
}
```

### Creating Layouts

Create a new layout using the artisan command:

```bash
php artisan make:flexible-layout HeroSection
```

Or create a layout manually:

```php
<?php

namespace App\Filament\Flexible\Layouts;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;

final class HeroLayout extends Layout
{
    protected ?string $name = 'hero';
    protected ?string $title = 'Hero Section';

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-rectangle-group')
            ->fields([
                TextInput::make('heading')
                    ->required()
                    ->maxLength(255),

                Textarea::make('subheading')
                    ->rows(2)
                    ->maxLength(500),
            ]);
    }
}
```

### Using Presets

Create a preset to bundle multiple layouts:

```bash
php artisan make:flexible-layout PageBuilder --preset
```

```php
<?php

namespace App\Filament\Flexible\Presets;

use IamGerwin\FilamentFlexibleContent\Layouts\Preset;
use App\Filament\Flexible\Layouts\HeroLayout;
use App\Filament\Flexible\Layouts\ContentLayout;

final class PageBuilder extends Preset
{
    public function register(): void
    {
        $this->addLayouts([
            HeroLayout::make(),
            ContentLayout::make(),
        ]);
    }
}
```

Use the preset in your form:

```php
FlexibleContent::make('content')
    ->preset(PageBuilder::class)
```

### Advanced Configuration

```php
FlexibleContent::make('content')
    ->layouts([/* ... */])
    ->minLayouts(1)              // Minimum number of layouts
    ->maxLayouts(10)             // Maximum number of layouts
    ->onlyLayouts(['hero'])      // Limit to specific layouts
    ->collapsible()              // Make blocks collapsible
    ->cloneable()                // Allow cloning blocks
    ->reorderable()              // Allow reordering blocks
    ->columnSpanFull()           // Full width
```

### Conditional Visibility with dependsOn

The FlexibleContent field supports conditional visibility based on other form fields:

```php
// Show flexible content only when type is 'national'
FlexibleContent::make('content')
    ->dependsOn('type', fn ($get) => $get('type') === 'national')
    ->layouts([/* ... */])

// Multiple field dependencies
FlexibleContent::make('content')
    ->dependsOn(['type', 'status'], function ($get) {
        return $get('type') === 'national' && $get('status') === 'published';
    })
    ->layouts([/* ... */])
```

You can also apply conditional visibility to individual layouts:

```php
class ConditionalLayout extends Layout
{
    protected function setUp(): void
    {
        parent::setUp();

        // Only show this layout when scope is 'global'
        $this->dependsOn('scope', fn ($get) => $get('scope') === 'global');

        $this->fields([
            TextInput::make('title')->required(),
        ]);
    }
}
```

### Database Integration

Add the cast to your model:

```php
use IamGerwin\FilamentFlexibleContent\Casts\FlexibleContentCast;

class Page extends Model
{
    protected $casts = [
        'content' => FlexibleContentCast::class,
    ];
}
```

### Accessing Content in Views

```blade
@foreach($page->content as $block)
    @switch($block->layout)
        @case('hero')
            <div class="hero-section">
                <h1>{{ $block->get('heading') }}</h1>
                <p>{{ $block->get('subheading') }}</p>
            </div>
            @break

        @case('content')
            <div class="content-section">
                {!! $block->get('content') !!}
            </div>
            @break
    @endswitch
@endforeach
```

### Working with Content Items

```php
// Check layout type
if ($block->is('hero')) {
    // Handle hero layout
}

// Access data
$heading = $block->get('heading');
$hasHeading = $block->has('heading');

// Access metadata
$order = $block->getMeta('order');

// Convert to array
$array = $block->toArray();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [iamgerwin](https://github.com/iamgerwin)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.