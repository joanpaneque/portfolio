<?php

use Inertia\Testing\AssertableInertia as Assert;

it('devuelve la página de inicio correctamente', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
        );
}); 