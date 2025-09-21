<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Tests;

use Filament\FilamentServiceProvider;
use IamGerwin\FilamentFlexibleContent\FilamentFlexibleContentServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentFlexibleContentServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        config()->set('app.key', 'base64:'.base64_encode('test-key-for-testing'));

        $migration = include __DIR__.'/../database/migrations/create_test_tables.php.stub';
        $migration->up();
    }
}
