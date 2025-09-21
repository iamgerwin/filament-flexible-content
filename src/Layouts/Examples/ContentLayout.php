<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Layouts\Examples;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;

final class ContentLayout extends Layout
{
    protected ?string $name = 'content';

    protected ?string $title = 'Rich Content';

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-document-text')
            ->columns(1)
            ->fields([
                TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255),

                RichEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'table',
                        'undo',
                    ])
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('content-attachments')
                    ->fileAttachmentsVisibility('public'),
            ]);
    }
}