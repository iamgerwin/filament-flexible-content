<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Layouts\Examples;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;

final class HeroLayout extends Layout
{
    protected ?string $name = 'hero';

    protected ?string $title = 'Hero Section';

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-rectangle-group')
            ->columns(2)
            ->fields([
                TextInput::make('heading')
                    ->label('Heading')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('subheading')
                    ->label('Subheading')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),

                FileUpload::make('background_image')
                    ->label('Background Image')
                    ->image()
                    ->imageEditor()
                    ->maxSize(5120)
                    ->directory('hero-backgrounds')
                    ->columnSpan(1),

                Select::make('background_position')
                    ->label('Background Position')
                    ->options([
                        'center' => 'Center',
                        'top' => 'Top',
                        'bottom' => 'Bottom',
                        'left' => 'Left',
                        'right' => 'Right',
                    ])
                    ->default('center')
                    ->columnSpan(1),

                TextInput::make('cta_text')
                    ->label('Call to Action Text')
                    ->maxLength(50),

                TextInput::make('cta_url')
                    ->label('Call to Action URL')
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt'),

                Toggle::make('full_height')
                    ->label('Full Height')
                    ->default(false)
                    ->helperText('Make the hero section full viewport height'),

                Toggle::make('dark_overlay')
                    ->label('Dark Overlay')
                    ->default(false)
                    ->helperText('Add a dark overlay to improve text readability'),
            ]);
    }
}