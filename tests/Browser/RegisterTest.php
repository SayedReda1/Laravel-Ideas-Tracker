<?php

declare(strict_types=1);

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'Test User')
        ->fill('email', 'test@test.com')
        ->fill('password', 'password')
        ->press('@register-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray(['name' => 'Test User']);
});

it('requires a valid email', function () {
    visit('/register')
        ->fill('name', 'Test User')
        ->fill('email', 'test@.com')
        ->fill('password', 'password')
        ->press('@register-button')
        ->assertPathIs('/register');
});
