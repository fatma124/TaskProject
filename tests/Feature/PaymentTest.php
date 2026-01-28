<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_payment_fails_if_order_not_confirmed()
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->postJson('/api/payments', [
            'order_id' => $order->id,
            'method' => 'credit_card'
        ]);

        $response->assertStatus(400);
    }

}
