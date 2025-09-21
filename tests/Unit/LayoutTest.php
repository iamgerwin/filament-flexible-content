<?php

declare(strict_types=1);

use Filament\Forms\Components\TextInput;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;

it('can create a layout instance', function () {
    $layout = createTestLayout('hero');

    expect($layout)->toBeInstanceOf(Layout::class)
        ->and($layout->name())->toBe('hero');
});

it('can set layout properties', function () {
    $layout = createTestLayout('content')
        ->title('Content Block')
        ->icon('heroicon-o-document-text')
        ->columns(3)
        ->limit(5);

    expect($layout->title())->toBe('Content Block')
        ->and($layout->icon())->toBe('heroicon-o-document-text')
        ->and($layout->columns())->toBe(3)
        ->and($layout->limit())->toBe(5);
});

it('can add fields to layout', function () {
    $fields = [
        TextInput::make('title'),
        TextInput::make('subtitle'),
    ];

    $layout = createTestLayout('test')->fields($fields);

    expect($layout->fields())->toBe($fields)
        ->and($layout->fields())->toHaveCount(2);
});

it('can control visibility', function () {
    $layout = createTestLayout('test');

    expect($layout->isVisible())->toBeTrue()
        ->and($layout->isHidden())->toBeFalse();

    $layout->hidden();

    expect($layout->isVisible())->toBeFalse()
        ->and($layout->isHidden())->toBeTrue();
});

it('can convert to array', function () {
    $layout = createTestLayout('hero')
        ->title('Hero Section')
        ->icon('heroicon-o-rectangle-group')
        ->columns(2)
        ->limit(1);

    $array = $layout->toArray();

    expect($array)->toBeArray()
        ->and($array['name'])->toBe('hero')
        ->and($array['title'])->toBe('Hero Section')
        ->and($array['icon'])->toBe('heroicon-o-rectangle-group')
        ->and($array['columns'])->toBe(2)
        ->and($array['limit'])->toBe(1);
});

it('can store and retrieve attributes', function () {
    $layout = createTestLayout('test')
        ->with('custom_key', 'custom_value')
        ->with('another_key', 123);

    expect($layout->get('custom_key'))->toBe('custom_value')
        ->and($layout->get('another_key'))->toBe(123)
        ->and($layout->get('non_existent', 'default'))->toBe('default');
});