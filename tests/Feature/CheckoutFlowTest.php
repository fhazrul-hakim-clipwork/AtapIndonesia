<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    public function test_checkout_flow_creates_order_and_shows_transfer_payment_page(): void
    {
        session(['cart' => [
            1 => ['product_id' => 1, 'quantity' => 2],
        ]]);

        $response = $this->post('/checkout/proses', [
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'telepon' => '08123456789',
            'alamat' => 'Jakarta Pusat',
            'metode_pembayaran' => 'transfer',
        ]);

        $response->assertRedirectContains('/checkout/pembayaran');
        $this->assertNotNull(session('pending_order'));

        $pendingOrder = session('pending_order');
        $this->assertSame('transfer', $pendingOrder['payment_method']);
        $this->assertSame('menunggu_pembayaran', $pendingOrder['status']);
        $this->assertEquals(495000, $pendingOrder['grand_total']);
        $this->assertEquals(15000, $pendingOrder['shipping_fee']);

        $paymentResponse = $this->get('/checkout/pembayaran');
        $paymentResponse->assertStatus(200);
        $paymentResponse->assertSee('Transfer Bank');
    }

    public function test_checkout_flow_shows_virtual_account_payment_page(): void
    {
        session(['pending_order' => [
            'order_id' => 'TRX-ABC123',
            'customer_name' => 'Budi Santoso',
            'telepon' => '08123456789',
            'alamat' => 'Jakarta Pusat',
            'grand_total' => 480000,
            'payment_method' => 'va',
            'status' => 'menunggu_pembayaran',
        ]]);

        $response = $this->get('/checkout/pembayaran');

        $response->assertStatus(200);
        $response->assertSee('BCA Virtual Account');
        $response->assertSee('8808000123456789');
    }
}
