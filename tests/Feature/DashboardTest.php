<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can access to dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});

test('guests can navigate to food', function () {
    $response = $this->get('/food');
    $response->assertStatus(200);
});
