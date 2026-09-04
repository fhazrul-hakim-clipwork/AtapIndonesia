<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Mengecek apakah sistem pembayaran lokal tersedia.
     *
     * Karena Xendit sudah dihapus, sistem pembayaran
     * sekarang menggunakan simulasi lokal.
     */
    public function isConfigured(): bool
    {
        return true;
    }

    /**
     * Membuat Virtual Account BCA SIMULASI.
     *
     * CATATAN:
     * Nomor ini bukan Virtual Account BCA sungguhan.
     * Nomor dibuat otomatis untuk kebutuhan testing
     * website AtapIndonesia.
     *
     * @return string Nomor Virtual Account simulasi
     */
    public function createBcaVirtualAccount(Order $order): string
    {
        try {
            /*
             * Jika order sudah memiliki nomor VA,
             * gunakan nomor yang sudah ada.
             */
            if (!empty($order->virtual_account)) {
                return $order->virtual_account;
            }

            /*
             * Membuat nomor VA simulasi 10 digit.
             *
             * Prefix 70001 digunakan sebagai penanda
             * Virtual Account simulasi AtapIndonesia.
             */
            $virtualAccountNumber =
                '70001' .
                str_pad(
                    (string) random_int(10000, 99999),
                    5,
                    '0',
                    STR_PAD_LEFT
                );

            /*
             * Pastikan nomor VA tidak sedang digunakan
             * oleh order lain.
             */
            while (
                Order::where(
                    'virtual_account',
                    $virtualAccountNumber
                )->exists()
            ) {
                $virtualAccountNumber =
                    '70001' .
                    str_pad(
                        (string) random_int(10000, 99999),
                        5,
                        '0',
                        STR_PAD_LEFT
                    );
            }

            /*
             * Simpan nomor VA ke order.
             */
            $order->update([
                'virtual_account' => $virtualAccountNumber,
            ]);

            Log::info(
                'Virtual Account simulasi berhasil dibuat',
                [
                    'order_id' => $order->order_id,
                    'virtual_account' => $virtualAccountNumber,
                ]
            );

            return $virtualAccountNumber;

        } catch (\Throwable $e) {

            Log::error(
                'Gagal membuat Virtual Account simulasi',
                [
                    'order_id' => $order->order_id,
                    'error' => $e->getMessage(),
                ]
            );

            throw new \Exception(
                'Gagal membuat Virtual Account: ' .
                $e->getMessage()
            );
        }
    }

    /**
     * Membuat halaman pembayaran lokal.
     *
     * Method ini dipertahankan agar kode lama yang masih
     * memanggil createInvoice() tidak langsung error.
     *
     * Tidak ada lagi koneksi ke Xendit.
     */
    public function createInvoice(
        Order $order,
        ?string $paymentMethod = null
    ): string {
        return route('orders.show', [
            'order_id' => $order->order_id,
        ]);
    }
}