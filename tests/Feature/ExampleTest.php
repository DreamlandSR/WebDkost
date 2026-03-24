<?php

test('example', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
})->skip('skip home page test - requires database data');
