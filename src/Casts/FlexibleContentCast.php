<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final readonly class FlexibleContentCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Collection
    {
        if (is_null($value)) {
            return collect();
        }

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return collect($value)->map(function (array $item) {
            return new FlexibleContentItem(
                layout: $item['layout'] ?? $item['type'] ?? '',
                data: $item['data'] ?? [],
                key: $item['key'] ?? uniqid(),
                meta: $item['meta'] ?? []
            );
        });
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        if (! is_array($value)) {
            return null;
        }

        $data = collect($value)->map(function ($item) {
            if ($item instanceof FlexibleContentItem) {
                return $item->toArray();
            }

            if (is_array($item)) {
                return [
                    'layout' => $item['layout'] ?? $item['type'] ?? '',
                    'data' => $item['data'] ?? [],
                    'key' => $item['key'] ?? uniqid(),
                    'meta' => $item['meta'] ?? [],
                ];
            }

            return [];
        })->filter()->values();

        return json_encode($data);
    }
}