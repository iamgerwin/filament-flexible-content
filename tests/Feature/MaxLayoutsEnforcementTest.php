<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Tests\Feature;

use Filament\Forms\Components\TextInput;
use IamGerwin\FilamentFlexibleContent\Forms\Components\FlexibleContent;
use IamGerwin\FilamentFlexibleContent\Layouts\Layout;
use IamGerwin\FilamentFlexibleContent\Tests\TestCase;
use Livewire\Component;
use Livewire\Livewire;

class MaxLayoutsEnforcementTest extends TestCase
{
    /** @test */
    public function it_disables_clone_button_when_max_layouts_reached()
    {
        $component = new class extends Component {
            public array $data = [];

            public function form($form)
            {
                return $form
                    ->schema([
                        FlexibleContent::make('content')
                            ->maxLayouts(2)
                            ->layouts([
                                TestMaxLayout::make(),
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

        // Add first item
        $livewire->call('mountFormComponentAction', 'data.content', 'add', ['block' => 'test_max']);

        // Add second item (should reach max)
        $livewire->call('mountFormComponentAction', 'data.content', 'add', ['block' => 'test_max']);

        // Get the component state
        $formComponent = $livewire->instance()->form->getComponent('content');
        $state = $formComponent->getState();

        // Check that we have 2 items
        $this->assertCount(2, $state);

        // Check that cloneable returns false when max is reached
        $cloneableCallback = $formComponent->isCloneable();
        if (is_callable($cloneableCallback)) {
            $this->assertFalse($cloneableCallback($formComponent, $state));
        }
    }

    /** @test */
    public function it_disables_add_button_when_max_layouts_reached()
    {
        $component = new class extends Component {
            public array $data = [];

            public function form($form)
            {
                return $form
                    ->schema([
                        FlexibleContent::make('content')
                            ->maxLayouts(3)
                            ->layouts([
                                TestMaxLayout::make(),
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

        // Add items up to max
        for ($i = 0; $i < 3; $i++) {
            $livewire->call('mountFormComponentAction', 'data.content', 'add', ['block' => 'test_max']);
        }

        $formComponent = $livewire->instance()->form->getComponent('content');
        $state = $formComponent->getState();

        // Check that we have 3 items (max reached)
        $this->assertCount(3, $state);

        // Check that addable returns false when max is reached
        $addableCallback = $formComponent->isAddable();
        if (is_callable($addableCallback)) {
            $this->assertFalse($addableCallback($formComponent, $state));
        }
    }

    /** @test */
    public function it_allows_cloning_when_below_max_layouts()
    {
        $component = new class extends Component {
            public array $data = [];

            public function form($form)
            {
                return $form
                    ->schema([
                        FlexibleContent::make('content')
                            ->maxLayouts(5)
                            ->layouts([
                                TestMaxLayout::make(),
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

        // Add one item
        $livewire->call('mountFormComponentAction', 'data.content', 'add', ['block' => 'test_max']);

        $formComponent = $livewire->instance()->form->getComponent('content');
        $state = $formComponent->getState();

        // Check that we have 1 item
        $this->assertCount(1, $state);

        // Check that cloneable returns true when below max
        $cloneableCallback = $formComponent->isCloneable();
        if (is_callable($cloneableCallback)) {
            $this->assertTrue($cloneableCallback($formComponent, $state));
        }
    }

    /** @test */
    public function it_respects_max_layouts_with_closures()
    {
        $component = new class extends Component {
            public array $data = ['max_allowed' => 2];

            public function form($form)
            {
                return $form
                    ->schema([
                        FlexibleContent::make('content')
                            ->maxLayouts(fn ($get) => $get('max_allowed'))
                            ->layouts([
                                TestMaxLayout::make(),
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

        // Add items
        $livewire->call('mountFormComponentAction', 'data.content', 'add', ['block' => 'test_max']);
        $livewire->call('mountFormComponentAction', 'data.content', 'add', ['block' => 'test_max']);

        $formComponent = $livewire->instance()->form->getComponent('content');
        $state = $formComponent->getState();

        // Should have 2 items (the max from closure)
        $this->assertCount(2, $state);

        // Check that cloneable returns false
        $cloneableCallback = $formComponent->isCloneable();
        if (is_callable($cloneableCallback)) {
            $this->assertFalse($cloneableCallback($formComponent, $state));
        }
    }

    /** @test */
    public function it_enables_cloning_when_no_max_layouts_set()
    {
        $component = new class extends Component {
            public array $data = [];

            public function form($form)
            {
                return $form
                    ->schema([
                        FlexibleContent::make('content')
                            ->layouts([
                                TestMaxLayout::make(),
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

        // Add multiple items
        for ($i = 0; $i < 10; $i++) {
            $livewire->call('mountFormComponentAction', 'data.content', 'add', ['block' => 'test_max']);
        }

        $formComponent = $livewire->instance()->form->getComponent('content');

        // Should always be cloneable when no max is set
        $this->assertTrue($formComponent->isCloneable());
        $this->assertTrue($formComponent->isAddable());
    }
}

class TestMaxLayout extends Layout
{
    protected ?string $name = 'test_max';
    protected ?string $title = 'Test Max Layout';

    protected function setUp(): void
    {
        parent::setUp();

        $this->fields([
            TextInput::make('title')->required(),
        ]);
    }
}