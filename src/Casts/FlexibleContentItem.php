<?php

declare(strict_types=1);

namespace IamGerwin\FilamentFlexibleContent\Casts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

final class FlexibleContentItem implements Arrayable, Jsonable, JsonSerializable
{
    public readonly string $key;

    public function __construct(
        public readonly string $layout,
        public readonly array $data = [],
        ?string $key = null,
        public readonly array $meta = []
    ) {
        $this->key = $key ?? uniqid();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }

    public function has(string $key): bool
    {
        return data_get($this->data, $key) !== null;
    }

    public function getMeta(string $key, mixed $default = null): mixed
    {
        return data_get($this->meta, $key, $default);
    }

    public function hasMeta(string $key): bool
    {
        return data_get($this->meta, $key) !== null;
    }

    public function is(string $layout): bool
    {
        return $this->layout === $layout;
    }

    public function isNot(string $layout): bool
    {
        return ! $this->is($layout);
    }

    public function toArray(): array
    {
        return [
            'layout' => $this->layout,
            'data' => $this->data,
            'key' => $this->key,
            'meta' => $this->meta,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
