<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Layouts Directory
    |--------------------------------------------------------------------------
    |
    | This value sets the default directory where layout classes are stored.
    | You can change this to any directory within your application.
    |
    */
    'layouts_directory' => app_path('Filament/Flexible/Layouts'),

    /*
    |--------------------------------------------------------------------------
    | Default Presets Directory
    |--------------------------------------------------------------------------
    |
    | This value sets the default directory where preset classes are stored.
    | You can change this to any directory within your application.
    |
    */
    'presets_directory' => app_path('Filament/Flexible/Presets'),

    /*
    |--------------------------------------------------------------------------
    | Auto-register Layouts
    |--------------------------------------------------------------------------
    |
    | When enabled, the package will automatically scan and register all
    | layout classes found in the layouts directory.
    |
    */
    'auto_register_layouts' => true,

    /*
    |--------------------------------------------------------------------------
    | Auto-register Presets
    |--------------------------------------------------------------------------
    |
    | When enabled, the package will automatically scan and register all
    | preset classes found in the presets directory.
    |
    */
    'auto_register_presets' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Enable caching of layout and preset discovery to improve performance.
    | The cache will be automatically cleared when running in debug mode.
    |
    */
    'cache' => [
        'enabled' => env('FLEXIBLE_CONTENT_CACHE', true),
        'key' => 'filament-flexible-content',
        'ttl' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | These are the default settings applied to all flexible content fields
    | unless overridden on individual field instances.
    |
    */
    'defaults' => [
        'collapsible' => true,
        'cloneable' => true,
        'reorderable' => true,
        'columns' => 2,
    ],
];
