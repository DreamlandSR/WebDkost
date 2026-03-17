<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);
    $response->assertRedirect();
    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
})->skip('skip - route /password needs verification');

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);
    $response->assertRedirect('/profile');
})->skip('skip - route /password needs verification');
