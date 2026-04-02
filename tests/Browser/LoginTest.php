<?php

use App\Models\User;

it('logins an user', function () {
    User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-user@test.com',
        'password' => 'password',
    ]);

    visit('/login')
        ->fill('email', 'test-user@test.com')
        ->fill('password', 'password')
        ->press('@login-button')
//        ->debug()
        ->assertPathIs('/');

    $this->assertAuthenticated();
});

it('logs out an user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/')
        ->press('@logout-button');
    //        ->debug()

    $this->assertGuest();
});
