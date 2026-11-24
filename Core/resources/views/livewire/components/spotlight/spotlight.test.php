<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('core::components.spotlight')
        ->assertStatus(200);
});

it('is closed by default', function () {
    Livewire::test('core::components.spotlight')
        ->assertSet('isOpen', false);
});

it('opens and closes', function () {
    $component = Livewire::test('core::components.spotlight');

    $component->call('open')
        ->assertSet('isOpen', true)
        ->assertDispatched('spotlight-opened');

    $component->call('close')
        ->assertSet('isOpen', false)
        ->assertDispatched('spotlight-closed');
});

it('searches for matching items', function () {
    app('spotlight')->addItem([
        'title' => 'Zebra Spotlight Test',
        'url' => '/zebra',
    ]);

    $component = Livewire::test('core::components.spotlight')
        ->set('searchTerm', 'Zebra');

    $results = $component->get('results');

    expect($results)->toHaveCount(1);
    expect($results[0]['title'])->toBe('Zebra Spotlight Test');
});

it('shows no results for unknown terms', function () {
    $component = Livewire::test('core::components.spotlight')
        ->set('searchTerm', 'no-such-spotlight-result');

    expect($component->get('results'))->toHaveCount(0);
});

it('cycles through the results', function () {
    app('spotlight')->addItem([
        'title' => 'Zebra One',
        'url' => '/zebra-one',
    ]);
    app('spotlight')->addItem([
        'title' => 'Zebra Two',
        'url' => '/zebra-two',
    ]);

    $component = Livewire::test('core::components.spotlight')
        ->set('searchTerm', 'Zebra');

    expect($component->get('selectedIndex'))->toBe(0);

    $component->call('selectNextResult');
    expect($component->get('selectedIndex'))->toBe(1);

    $component->call('selectNextResult');
    expect($component->get('selectedIndex'))->toBe(0);

    $component->call('selectPreviousResult');
    expect($component->get('selectedIndex'))->toBe(1);
});

it('hides items the user is not allowed to view', function () {
    $this->actingAs(makeUser());

    app('spotlight')->addItem([
        'title' => 'Secret Zebra Page',
        'url' => '/secret',
        'permissions' => 'admin.dashboard',
    ]);

    $component = Livewire::test('core::components.spotlight')
        ->set('searchTerm', 'Secret Zebra');

    expect($component->get('results'))->toHaveCount(0);
});
