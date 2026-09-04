<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShippingFlowTest extends TestCase
{
    public function test_shipping_page_shows_delivery_progress(): void
    {
        session(['pending_order' => [
            'order_id' => 'TRX-ABC123',
            'customer_name' => 'Budi Santoso',
            'telepon' => '08123456789',
            'alamat' => 'Jakarta Pusat',
            'grand_total' => 480000,
            'payment_method' => 'transfer',
            'status' => 'siap_dikirim',
        ]]);

        $response = $this->get('/checkout/shipping');

        $response->assertStatus(200);
        $response->assertSee('Pengiriman');
        $response->assertSee('Siap Dikirim');
        $response->assertSee('Estimasi');
    }
}
