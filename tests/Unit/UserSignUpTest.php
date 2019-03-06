<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSignUpTest extends TestCase
{
    /**
     * Test creating a user
     *
     * @return void
     */
    public function testUserCreation()
    {
        $response = $this->json('POST', 'api/register', ['firstname' => 'mary',
            'lastname' => 'jane',
            'username' => 'mary',
            'email' => 'mary@gmail.com',
            'date_of_birth' => '12-10-1994',
            'password' => '123456'
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }
}
