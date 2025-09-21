<?php

declare(strict_types=1);

use IamGerwin\FilamentFlexibleContent\Casts\FlexibleContentItem;

it('can create a flexible content item', function () {
    $item = new FlexibleContentItem(
        layout: 'hero',
        data: ['title' => 'Test Title'],
        key: 'test-key',
        meta: ['order' => 1]
    );

    expect($item->layout)->toBe('hero')
        ->and($item->data)->toBe(['title' => 'Test Title'])
        ->and($item->key)->toBe('test-key')
        ->and($item->meta)->toBe(['order' => 1]);
});

it('generates key if not provided', function () {
    $item = new FlexibleContentItem(
        layout: 'content',
        data: []
    );

    expect($item->key)->not->toBeEmpty()
        ->and($item->key)->toBeString();
});

it('can check layout type', function () {
    $item = new FlexibleContentItem(layout: 'hero');

    expect($item->is('hero'))->toBeTrue()
        ->and($item->is('content'))->toBeFalse()
        ->and($item->isNot('content'))->toBeTrue()
        ->and($item->isNot('hero'))->toBeFalse();
});

it('can access data with dot notation', function () {
    $item = new FlexibleContentItem(
        layout: 'test',
        data: [
            'title' => 'Main Title',
            'settings' => [
                'color' => 'blue',
                'size' => 'large'
            ]
        ]
    );

    expect($item->get('title'))->toBe('Main Title')
        ->and($item->get('settings.color'))->toBe('blue')
        ->and($item->get('settings.size'))->toBe('large')
        ->and($item->get('non.existent', 'default'))->toBe('default');
});

it('can check if data exists', function () {
    $item = new FlexibleContentItem(
        layout: 'test',
        data: [
            'title' => 'Test',
            'empty' => null,
            'nested' => ['key' => 'value']
        ]
    );

    expect($item->has('title'))->toBeTrue()
        ->and($item->has('empty'))->toBeFalse()
        ->and($item->has('nested.key'))->toBeTrue()
        ->and($item->has('non.existent'))->toBeFalse();
});

it('can access metadata', function () {
    $item = new FlexibleContentItem(
        layout: 'test',
        data: [],
        meta: [
            'order' => 1,
            'visibility' => 'public',
            'settings' => ['theme' => 'dark']
        ]
    );

    expect($item->getMeta('order'))->toBe(1)
        ->and($item->getMeta('visibility'))->toBe('public')
        ->and($item->getMeta('settings.theme'))->toBe('dark')
        ->and($item->getMeta('non.existent', 'default'))->toBe('default');
});

it('can check if metadata exists', function () {
    $item = new FlexibleContentItem(
        layout: 'test',
        data: [],
        meta: ['order' => 1, 'empty' => null]
    );

    expect($item->hasMeta('order'))->toBeTrue()
        ->and($item->hasMeta('empty'))->toBeFalse()
        ->and($item->hasMeta('non.existent'))->toBeFalse();
});

it('can convert to array', function () {
    $item = new FlexibleContentItem(
        layout: 'hero',
        data: ['title' => 'Test'],
        key: 'abc123',
        meta: ['order' => 1]
    );

    $array = $item->toArray();

    expect($array)->toBeArray()
        ->and($array['layout'])->toBe('hero')
        ->and($array['data'])->toBe(['title' => 'Test'])
        ->and($array['key'])->toBe('abc123')
        ->and($array['meta'])->toBe(['order' => 1]);
});

it('can convert to json', function () {
    $item = new FlexibleContentItem(
        layout: 'content',
        data: ['text' => 'Hello'],
        key: 'xyz',
        meta: []
    );

    $json = $item->toJson();

    expect($json)->toBeString()
        ->and(json_decode($json, true))->toEqual([
            'layout' => 'content',
            'data' => ['text' => 'Hello'],
            'key' => 'xyz',
            'meta' => []
        ]);
});

it('can be cast to string', function () {
    $item = new FlexibleContentItem(
        layout: 'test',
        data: ['foo' => 'bar']
    );

    $string = (string) $item;

    expect($string)->toBeString()
        ->and(json_decode($string, true)['layout'])->toBe('test')
        ->and(json_decode($string, true)['data'])->toBe(['foo' => 'bar']);
});