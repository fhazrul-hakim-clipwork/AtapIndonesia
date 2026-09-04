<?php

namespace Tests\Feature;

use Tests\TestCase;

class PaymentShippingIntegrationTest extends TestCase
{
    public function test_payment_callback_marks_order_as_paid(): void
    {
        session(['pending_order' => [
            'order_id' => 'TRX-ABC123',
            'customer_name' => 'Budi Santoso',
            'telepon' => '08123456789',
            'alamat' => 'Jakarta Pusat',
            'grand_total' => 480000,
            'payment_method' => 'bank',
            'status' => 'menunggu_pembayaran',
        ]]);

        $response = $this->post('/payment/callback', [
            'order_id' => 'TRX-ABC123',
            'status' => 'settlement',
        ]);

        $response->assertStatus(200);
        $this->assertSame('dibayar', session('pending_order')['status']);
    }
}
