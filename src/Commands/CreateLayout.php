<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

final class CreateLayout extends Command
{
    protected $signature = 'make:flexible-layout {name} {--preset=}';

    protected $description = 'Create a new flexible content layout';

    private Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $preset = $this->option('preset');

        $className = Str::studly($name);
        $namespace = $preset
            ? 'App\\Filament\\Flexible\\Presets'
            : 'App\\Filament\\Flexible\\Layouts';

        $path = $preset
            ? app_path("Filament/Flexible/Presets/{$className}.php")
            : app_path("Filament/Flexible/Layouts/{$className}.php");

        if ($this->files->exists($path)) {
            $this->error("The {$className} already exists!");
            return self::FAILURE;
        }

        $this->makeDirectory($path);

        $stub = $preset ? $this->getPresetStub() : $this->getLayoutStub();
        $contents = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ name }}'],
            [$namespace, $className, Str::kebab($name)],
            $stub
        );

        $this->files->put($path, $contents);

        $type = $preset ? 'Preset' : 'Layout';
        $this->info("{$type} [{$path}] created successfully.");

        return self::SUCCESS;
    }

    private function makeDirectory(string $path): void
    {
        $directory = dirname($path);

        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    private function getLayoutStub(): string
    {
        return <<<'PHP'
<?php

declare(strict_types=1);

namespace {{ namespace }};

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;

final class {{ class }} extends Layout
{
    protected ?string $name = '{{ name }}';

    protected function setUp(): void
    {
        parent::setUp();

        $this->fields([
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),

            Textarea::make('content')
                ->label('Content')
                ->rows(4)
                ->required(),
        ]);
    }
}
PHP;
    }

    private function getPresetStub(): string
    {
        return <<<'PHP'
<?php

declare(strict_types=1);

namespace {{ namespace }};

use IamGerwin\FilamentFlexibleContent\Layouts\Preset;

final class {{ class }} extends Preset
{
    public function register(): void
    {
        // Register your layouts here
        // $this->addLayout(new MyLayout());
    }
}
PHP;
    }
}