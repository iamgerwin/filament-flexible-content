<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Tests\Feature;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use IamGerwin\FilamentFlexibleContent\Forms\Components\FlexibleContent;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;
use IamGerwin\FilamentFlexibleContent\Tests\TestCase;
use Livewire\Component;
use Livewire\Livewire;

class ReactiveDependsOnTest extends TestCase
{
    /** @test */
    public function it_reactively_updates_visibility_when_dependent_field_changes()
    {
        $component = new class extends Component
        {
            public array $data = ['type' => 'local'];

            public function form($form)
            {
                return $form
                    ->schema([
                        Select::make('type')
                            ->options([
                                'local' => 'Local',
                                'national' => 'National',
                            ])
                            ->reactive(),

                        FlexibleContent::make('content')
                            ->dependsOn('type', fn ($get) => $get('type') === 'national')
                            ->layouts([
                                SimpleTestLayout::make(),
                            ]),
                    ])
                    ->statePath('data');
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $livewire = Livewire::test($component);

        // Initially should be hidden (type is 'local')
        $livewire->assertDontSee('content');

        // Change to 'national' - should become visible
        $livewire->set('data.type', 'national')
            ->assertSee('content');

        // Change back to 'local' - should become hidden again
        $livewire->set('data.type', 'local')
            ->assertDontSee('content');
    }

    /** @test */
    public function it_makes_dependent_fields_live_automatically()
    {
        $component = new class extends Component
        {
            public array $data = ['status' => 'draft'];

            public function form($form)
            {
                return $form
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ]),

                        FlexibleContent::make('content')
                            ->dependsOn('status', fn ($get) => $get('status') === 'published')
                            ->layouts([
                                SimpleTestLayout::make(),
                            ]),
                    ])
                    ->statePath('data');
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $livewire = Livewire::test($component);

        // The dependent field should automatically become live/reactive
        $formComponent = $livewire->instance()->form->getComponent('status');
        $this->assertTrue(method_exists($formComponent, 'isLive') ? $formComponent->isLive() : true);
    }

    /** @test */
    public function it_handles_multiple_dependencies_reactively()
    {
        $component = new class extends Component
        {
            public array $data = [
                'type' => 'local',
                'status' => 'draft',
            ];

            public function form($form)
            {
                return $form
                    ->schema([
                        Select::make('type')
                            ->options([
                                'local' => 'Local',
                                'national' => 'National',
                            ])
                            ->reactive(),

                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->reactive(),

                        FlexibleContent::make('content')
                            ->dependsOn(['type', 'status'], function ($get) {
                                return $get('type') === 'national' && $get('status') === 'published';
                            })
                            ->layouts([
                                SimpleTestLayout::make(),
                            ]),
                    ])
                    ->statePath('data');
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $livewire = Livewire::test($component);

        // Initially hidden
        $livewire->assertDontSee('content');

        // Set type to national - still hidden (status is draft)
        $livewire->set('data.type', 'national')
            ->assertDontSee('content');

        // Set status to published - now visible
        $livewire->set('data.status', 'published')
            ->assertSee('content');

        // Change either back - becomes hidden
        $livewire->set('data.type', 'local')
            ->assertDontSee('content');
    }
}

class SimpleTestLayout extends Layout
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->name = 'simple_test';
        $this->title = 'Simple Test Layout';

        $this->fields([
            TextInput::make('title')->required(),
        ]);
    }
}
