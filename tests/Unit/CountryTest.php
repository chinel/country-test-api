<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryTest extends TestCase
{
    /**
     * Test creating a product.
     *
     * @return void
     */
    public function testCreateCountry()
    {
        $data = [
            'name' => "Nigeria",
            'continent' => "Africa",
        ];
        $user = factory(\App\User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/countries',$data);
        $response->assertStatus(200);
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Country Created!"]);
        $response->assertJson(['data' => $data]);
    }
}
