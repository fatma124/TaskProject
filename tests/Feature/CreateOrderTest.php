<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeader(
            'Authorization',
            "Bearer $token"
        )->postJson('/api/orders', [
            'items' => [
                [
                    'product_name' => 'Laptop',
                    'quantity' => 2,
                    'price' => 1000
                ]
            ]
        ]);

        $response->assertStatus(201)
                ->assertJsonFragment(['total' => 2000]);
    }

}
