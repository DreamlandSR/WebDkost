<?php
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

test('profile page is displayed', function () {
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->get('/profile');
    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->put('/profile', [
            'nama' => 'Test User',    // ← ganti name ke nama
            'email' => 'test@example.com',
        ]);
    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');
    $user->refresh();
    $this->assertSame('Test User', $user->nama);  // ← ganti name ke nama
    $this->assertSame('test@example.com', $user->email);
});
