<?php

use Livewire\Livewire;
use Modules\Core\Models\Session;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::sessions')
        ->assertStatus(200);
});

it('logs out a session when the password is confirmed', function () {
    $user = makeUser();
    $session = new Session;
    $session->id = str()->random(40);
    $session->user_id = $user->id;
    $session->payload = '[]';
    $session->last_activity = time();
    $session->save();

    $this->actingAs($user);

    session(['core.password_confirmed_at' => time()]);

    Livewire::test('account::sessions')
        ->call('logoutSession', $session->id);

    expect(Session::find($session->id))->toBeNull();
});

it('asks for password confirmation before logging out a session', function () {
    $user = makeUser();
    $session = new Session;
    $session->id = str()->random(40);
    $session->user_id = $user->id;
    $session->payload = '[]';
    $session->last_activity = time();
    $session->save();

    $this->actingAs($user);

    Livewire::test('account::sessions')
        ->call('logoutSession', $session->id)
        ->assertDispatched('openModal', 'core::components.modals.password-confirmation');

    expect(Session::find($session->id))->not->toBeNull();
});

it('logs out an app session (api token) when the password is confirmed', function () {
    $user = makeUser();
    $user->createToken('Test App');
    $tokenId = (string) $user->tokens()->first()->id;

    $this->actingAs($user);

    session(['core.password_confirmed_at' => time()]);

    Livewire::test('account::sessions')
        ->call('logoutAppSession', $tokenId);

    expect($user->tokens()->count())->toBe(0);
});

it('opens the login qr code modal', function () {
    $user = makeUser();
    $this->actingAs($user);

    session(['core.password_confirmed_at' => time()]);

    Livewire::test('account::sessions')
        ->call('showLoginQrCode')
        ->assertDispatched('openModal', 'account::components.modals.login-qr-code');
});
