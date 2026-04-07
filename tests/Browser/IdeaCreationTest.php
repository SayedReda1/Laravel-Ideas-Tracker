<?php

use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

it("creates new idea", function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    visit(route('idea.index'))
        ->click('@create-idea-button')
        ->fill('@idea-title', 'Buy a car')
        ->fill('@idea-description', 'Bla Bla Bla')
        ->click('@idea-status-in_progress')
        ->fill('@link-field', 'https://example.com')
        ->click('@add-new-link-button')
        ->fill('@link-field','https://laravel.com')
        ->click('@add-new-link-button')
        ->click('Create')
        ->assertPathIs('/ideas')
        ->assertSee('Buy a car');

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'Buy a car',
        'description' => 'Bla Bla Bla',
        'status' => 'in_progress',
        'links' => ['https://example.com', 'https://laravel.com'],
    ]);
});
