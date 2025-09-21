<?php

declare(strict_types=1);

use IamGerwin\FilamentFlexibleContent\Layouts\Preset;

it('can create a preset instance', function () {
    $preset = new class extends Preset {
        public function register(): void
        {
            // Empty registration for testing
        }
    };

    expect($preset)->toBeInstanceOf(Preset::class)
        ->and($preset->isEmpty())->toBeTrue();
});

it('can add layouts to preset', function () {
    $preset = new class extends Preset {
        public function register(): void {}
    };

    $layout1 = createTestLayout('hero');
    $layout2 = createTestLayout('content');

    $preset->addLayout($layout1)
           ->addLayout($layout2);

    expect($preset->count())->toBe(2)
        ->and($preset->hasLayout('hero'))->toBeTrue()
        ->and($preset->hasLayout('content'))->toBeTrue()
        ->and($preset->isNotEmpty())->toBeTrue();
});

it('can add multiple layouts at once', function () {
    $preset = new class extends Preset {
        public function register(): void {}
    };

    $layouts = [
        createTestLayout('hero'),
        createTestLayout('content'),
        createTestLayout('gallery'),
    ];

    $preset->addLayouts($layouts);

    expect($preset->count())->toBe(3)
        ->and($preset->hasLayout('hero'))->toBeTrue()
        ->and($preset->hasLayout('content'))->toBeTrue()
        ->and($preset->hasLayout('gallery'))->toBeTrue();
});

it('can remove layout from preset', function () {
    $preset = new class extends Preset {
        public function register(): void {}
    };

    $preset->addLayout(createTestLayout('hero'))
           ->addLayout(createTestLayout('content'));

    expect($preset->count())->toBe(2);

    $preset->removeLayout('hero');

    expect($preset->count())->toBe(1)
        ->and($preset->hasLayout('hero'))->toBeFalse()
        ->and($preset->hasLayout('content'))->toBeTrue();
});

it('can get specific layout from preset', function () {
    $preset = new class extends Preset {
        public function register(): void {}
    };

    $heroLayout = createTestLayout('hero')->title('Hero Section');
    $preset->addLayout($heroLayout);

    $retrieved = $preset->getLayout('hero');

    expect($retrieved)->toBe($heroLayout)
        ->and($retrieved?->title())->toBe('Hero Section')
        ->and($preset->getLayout('non-existent'))->toBeNull();
});

it('can convert preset to array', function () {
    $preset = new class extends Preset {
        public function register(): void {}
    };

    $preset->addLayouts([
        createTestLayout('hero')->title('Hero'),
        createTestLayout('content')->title('Content'),
    ]);

    $array = $preset->toArray();

    expect($array)->toBeArray()
        ->and($array)->toHaveCount(2)
        ->and($array['hero']['name'])->toBe('hero')
        ->and($array['hero']['title'])->toBe('Hero')
        ->and($array['content']['name'])->toBe('content')
        ->and($array['content']['title'])->toBe('Content');
});