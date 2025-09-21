<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use IamGerwin\FilamentFlexibleContent\Commands\CreateLayout;

final class FilamentFlexibleContentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-flexible-content';

    public static string $viewNamespace = 'filament-flexible-content';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('iamgerwin/filament-flexible-content');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        $this->app->scoped('filament-flexible-content', fn () => new FilamentFlexibleContent());
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            assets: $this->getAssets(),
            package: 'iamgerwin/filament-flexible-content'
        );
    }

    protected function getAssets(): array
    {
        return [
            Js::make('filament-flexible-content', __DIR__ . '/../resources/dist/filament-flexible-content.js'),
        ];
    }

    protected function getCommands(): array
    {
        return [
            CreateLayout::class,
        ];
    }

    protected function getMigrations(): array
    {
        return [];
    }
}