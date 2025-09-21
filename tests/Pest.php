<?php

declare(strict_types=1);

use IamGerwin\FilamentFlexibleContent\Tests\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function createTestLayout(string $name = 'test'): \IamGerwin\FilamentFlexibleContent\Layouts\Layout
{
    return new class($name) extends \IamGerwin\FilamentFlexibleContent\Layouts\Layout
    {
        public function __construct(private string $layoutName)
        {
            $this->name = $layoutName;
            parent::__construct();
        }
    };
}
