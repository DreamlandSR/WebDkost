<?php

test('new users can register', function () {
    $response = $this->post('/register', [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertRedirect();
})->skip('skip - register only accessible by admin');
