<?php
use App\Models\User;

test('new users can register', function () {
    $response = $this->post('/register', [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $this->assertAuthenticated();
    $response->assertRedirect();
});
