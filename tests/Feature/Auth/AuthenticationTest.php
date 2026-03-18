<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'role'     => 'admin',    // ← set role spesifik
    ]);
    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);
    $this->assertAuthenticated();
    $response->assertRedirect();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'role'     => 'admin',    // ← set role spesifik
    ]);
    $this->post('/login', [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ]);
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create([
        'role' => 'admin',        // ← set role spesifik
    ]);
    $response = $this->actingAs($user)->post('/logout');
    $this->assertGuest();
    $response->assertRedirect();
});
