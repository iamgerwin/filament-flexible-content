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
        $component = $this->createFormWithDependency();

        // Test when condition is not met
        $component->fill(['type' => 'local']);
        $this->assertFalse($component->form->getComponent('content')->isVisible());

        // Test when condition is met
        $component->fill(['type' => 'national']);
        $this->assertTrue($component->form->getComponent('content')->isVisible());
    }

    /** @test */
    public function it_can_conditionally_show_layouts_based_on_dependency()
    {
        $layout = $this->createLayoutWithDependency();

        // Create a mock $get function
        $getLocal = fn($field) => $field === 'scope' ? 'local' : null;
        $getNational = fn($field) => $field === 'scope' ? 'national' : null;

        // Test when condition is not met
        $this->assertFalse($layout->isVisible($getLocal));

        // Test when condition is met
        $this->assertTrue($layout->isVisible($getNational));
    }

    /** @test */
    public function it_supports_multiple_field_dependencies()
    {
        $component = Component::extend('TestComponent', [
            'form' => function () {
                return $this->form([
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
                ]);
            },
        ]);

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
            ->dependsOn('type', fn($get) => $get('type') === 'national');

        $getNational = fn($field) => $field === 'type' ? 'national' : null;

        // Even if dependency is met, original visibility should be respected
        $this->assertFalse($layout->isVisible($getNational));
    }

    protected function createFormWithDependency()
    {
        return Component::extend('TestComponent', [
            'form' => function () {
                return $this->form([
                    Select::make('type')
                        ->options(['local' => 'Local', 'national' => 'National']),
                    FlexibleContent::make('content')
                        ->dependsOn('type', fn($get) => $get('type') === 'national')
                        ->layouts([
                            TestLayout::make(),
                        ]),
                ]);
            },
        ]);
    }

    protected function createLayoutWithDependency(): Layout
    {
        return TestLayout::make()
            ->dependsOn('scope', fn($get) => $get('scope') === 'national');
    }
}

class TestLayout extends Layout
{
    protected ?string $name = 'test_layout';
    protected ?string $title = 'Test Layout';

    protected function setUp(): void
    {
        parent::setUp();

        $this->fields([
            TextInput::make('title')->required(),
            TextInput::make('description'),
        ]);
    }
}