<?php

namespace Tests\Feature\Feature\Auth;

use Tests\TestCase;

class LoginWithRolesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
