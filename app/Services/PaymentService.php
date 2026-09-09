<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Mengecek apakah sistem pembayaran tersedia.
     *
     * Saat ini sistem pembayaran menggunakan
     * simulasi lokal, bukan Xendit.
     */
    public function isConfigured(): bool
    {
        return true;
    }

    /**
     * Membuat Virtual Account BCA SIMULASI.
     *
     * CATATAN:
     * Nomor VA ini adalah nomor simulasi untuk testing
     * website AtapIndonesia dan bukan VA BCA sungguhan.
     */
    public function createBcaVirtualAccount(
        Order $order
    ): string {

        try {

            // =====================================================
            // JIKA SUDAH ADA VA
            // =====================================================

            if (!empty($order->virtual_account)) {

                return $order->virtual_account;
            }

            // =====================================================
            // BUAT VA BARU
            // =====================================================

            do {

                /*
                 * Prefix:
                 * 70001 = penanda VA simulasi AtapIndonesia
                 *
                 * Total:
                 * 5 digit prefix + 5 digit random = 10 digit
                 */

                $virtualAccountNumber =
                    '70001'
                    . str_pad(
                        (string) random_int(
                            10000,
                            99999
                        ),
                        5,
                        '0',
                        STR_PAD_LEFT
                    );

            } while (
                Order::where(
                    'virtual_account',
                    $virtualAccountNumber
                )->exists()
            );

            // =====================================================
            // SIMPAN VA
            // =====================================================

            $order->update([
                'virtual_account' =>
                    $virtualAccountNumber,
            ]);

            Log::info(
                'Virtual Account BCA simulasi berhasil dibuat.',
                [
                    'order_id' =>
                        $order->order_id,

                    'virtual_account' =>
                        $virtualAccountNumber,
                ]
            );

            return $virtualAccountNumber;

        } catch (\Throwable $e) {

            Log::error(
                'Gagal membuat Virtual Account BCA simulasi.',
                [
                    'order_id' =>
                        $order->order_id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            throw new \Exception(
                'Gagal membuat Virtual Account: '
                . $e->getMessage()
            );
        }
    }

    /**
     * Membuat halaman pembayaran lokal.
     *
     * Untuk metode VA / BCA:
     * - membuat VA
     * - menyimpan VA ke order
     * - mengarahkan user ke halaman order
     *
     * Untuk metode bank:
     * - langsung menuju halaman order
     */
    public function createInvoice(
        Order $order,
        ?string $paymentMethod = null
    ): string {

        // =====================================================
        // NORMALISASI METODE PEMBAYARAN
        // =====================================================

        $method =
            strtolower(
                trim(
                    (string) (
                        $paymentMethod
                        ?? $order->payment_method
                        ?? 'va'
                    )
                )
            );

        // =====================================================
        // VIRTUAL ACCOUNT BCA
        // =====================================================

        if (
            in_array(
                $method,
                [
                    'va',
                    'bca',
                    'bca_va',
                    'virtual_account',
                    'virtual-account',
                ],
                true
            )
        ) {

            $virtualAccount =
                $this->createBcaVirtualAccount(
                    $order
                );

            Log::info(
                'Pembayaran VA BCA berhasil disiapkan.',
                [
                    'order_id' =>
                        $order->order_id,

                    'virtual_account' =>
                        $virtualAccount,

                    'amount' =>
                        $order->grand_total,
                ]
            );
        }

        // =====================================================
        // TRANSFER BANK MANUAL
        // =====================================================

        elseif (
            $method === 'bank'
        ) {

            Log::info(
                'Pembayaran transfer bank manual dipilih.',
                [
                    'order_id' =>
                        $order->order_id,

                    'amount' =>
                        $order->grand_total,
                ]
            );
        }

        // =====================================================
        // METODE LAIN
        // =====================================================

        else {

            Log::info(
                'Metode pembayaran lokal diproses.',
                [
                    'order_id' =>
                        $order->order_id,

                    'payment_method' =>
                        $method,
                ]
            );
        }

        // =====================================================
        // ARAHKAN KE HALAMAN ORDER
        // =====================================================

        return route(
            'orders.show',
            [
                'order_id' =>
                    $order->order_id,
            ]
        );
    }
}