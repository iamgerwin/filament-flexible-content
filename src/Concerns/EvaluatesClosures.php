<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Concerns;

use Closure;

trait EvaluatesClosures
{
    protected function evaluate(mixed $value, array $parameters = []): mixed
    {
        if ($value instanceof Closure) {
            return app()->call(
                $value,
                array_merge($this->getDefaultEvaluationParameters(), $parameters)
            );
        }

        return $value;
    }

    protected function getDefaultEvaluationParameters(): array
    {
        return ['component' => $this];
    }
}
