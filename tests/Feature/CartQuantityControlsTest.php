<?php

namespace Tests\Feature;

use Tests\TestCase;

class CartQuantityControlsTest extends TestCase
{
    public function test_cart_update_accepts_quantity_delta_for_quick_adjustment(): void
    {
        session(['cart' => [
            1 => ['product_id' => 1, 'quantity' => 2],
        ]]);

        $response = $this->post('/keranjang/update', [
            'product_id' => 1,
            'delta' => 1,
        ]);

        $response->assertRedirect('/keranjang');
        $this->assertSame(3, session('cart')[1]['quantity']);
    }
}
