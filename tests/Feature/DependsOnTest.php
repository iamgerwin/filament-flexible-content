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

class DependsOnTest extends TestCase
{
    /** @test */
    public function it_can_conditionally_show_flexible_content_based_on_dependency()
    {
        $component = new class extends Component
        {
            public array $data = ['type' => 'local'];

            public function form($form)
            {
                return $form
                    ->schema([
                        Select::make('type')
                            ->options(['local' => 'Local', 'national' => 'National']),
                        FlexibleContent::make('content')
                            ->dependsOn('type', fn ($get) => $get('type') === 'national')
                            ->layouts([
                                TestLayout::make(),
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

        // Test when condition is not met
        $this->assertFalse($livewire->instance()->form->getComponent('content')->isVisible());

        // Change to national and test
        $livewire->set('data.type', 'national');
        $this->assertTrue($livewire->instance()->form->getComponent('content')->isVisible());
    }

    /** @test */
    public function it_can_conditionally_show_layouts_based_on_dependency()
    {
        $layout = TestLayout::make()
            ->dependsOn('scope', fn ($get) => $get('scope') === 'national');

        // Create a mock $get function
        $getLocal = fn ($field) => $field === 'scope' ? 'local' : null;
        $getNational = fn ($field) => $field === 'scope' ? 'national' : null;

        // Test when condition is not met
        $this->assertFalse($layout->isVisible($getLocal));

        // Test when condition is met
        $this->assertTrue($layout->isVisible($getNational));
    }

    /** @test */
    public function it_supports_multiple_field_dependencies()
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
                            ->options(['local' => 'Local', 'national' => 'National']),
                        Select::make('status')
                            ->options(['draft' => 'Draft', 'published' => 'Published']),
                        FlexibleContent::make('content')
                            ->dependsOn(['type', 'status'], function ($get) {
                                return $get('type') === 'national' && $get('status') === 'published';
                            })
                            ->layouts([
                                TestLayout::make(),
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

        // Test when both conditions are not met
        $livewire->set('data.type', 'local')
            ->set('data.status', 'draft');
        $this->assertFalse($livewire->instance()->form->getComponent('content')->isVisible());

        // Test when only one condition is met
        $livewire->set('data.type', 'national')
            ->set('data.status', 'draft');
        $this->assertFalse($livewire->instance()->form->getComponent('content')->isVisible());

        // Test when both conditions are met
        $livewire->set('data.type', 'national')
            ->set('data.status', 'published');
        $this->assertTrue($livewire->instance()->form->getComponent('content')->isVisible());
    }

    /** @test */
    public function it_preserves_original_visibility_with_dependencies()
    {
        $layout = TestLayout::make()
            ->visible(false)
            ->dependsOn('type', fn ($get) => $get('type') === 'national');

        $getNational = fn ($field) => $field === 'type' ? 'national' : null;

        // Even if dependency is met, original visibility should be respected
        $this->assertFalse($layout->isVisible($getNational));
    }
}

class TestLayout extends Layout
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->name = 'test_layout';
        $this->title = 'Test Layout';

        $this->fields([
            TextInput::make('title')->required(),
            TextInput::make('description'),
        ]);
    }
}
