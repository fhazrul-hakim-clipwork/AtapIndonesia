<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ShippingService
{
    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI GUDANG
    |--------------------------------------------------------------------------
    */

    private float $originLat = -7.5167;
    private float $originLng = 109.1500;

    private string $originAddress =
        'Jalan Karang Duren RT.2/RW.3 Sokaraja Lor, Sokaraja, Kabupaten Banyumas, Jawa Tengah';


    /*
    |--------------------------------------------------------------------------
    | TARIF ONGKIR
    |--------------------------------------------------------------------------
    |
    | Anda bisa mengubah tarif di bagian ini tanpa mengubah kode lainnya.
    |
    */

    private array $shippingRates = [

        'lalamove' => [
            [
                'max_km' => 5,
                'fee' => 15000,
            ],
            [
                'max_km' => 10,
                'fee' => 25000,
            ],
            [
                'max_km' => 20,
                'fee' => 40000,
            ],
            [
                'max_km' => 30,
                'fee' => 60000,
            ],
            [
                'max_km' => 50,
                'fee' => 90000,
            ],
            [
                'max_km' => INF,
                'fee' => 125000,
            ],
        ],

        'deliveree' => [
            [
                'max_km' => 5,
                'fee' => 20000,
            ],
            [
                'max_km' => 10,
                'fee' => 30000,
            ],
            [
                'max_km' => 20,
                'fee' => 50000,
            ],
            [
                'max_km' => 30,
                'fee' => 75000,
            ],
            [
                'max_km' => 50,
                'fee' => 110000,
            ],
            [
                'max_km' => INF,
                'fee' => 150000,
            ],
        ],
    ];


    /*
    |--------------------------------------------------------------------------
    | HITUNG ONGKIR
    |--------------------------------------------------------------------------
    */

    public function calculateShippingFee(
        string $address,
        array $items = [],
        ?string $provider = null
    ): array {

        $address = trim($address);

        if ($address === '') {
            return [
                'provider' => $provider ? ucfirst($provider) : 'Manual',
                'courier' => $provider ? ucfirst($provider) : 'Manual',
                'fee' => 0,
                'distance_km' => 0,
                'eta_minutes' => null,
                'service_type' => null,
                'all_quotes' => [],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DETEKSI LOKASI
        |--------------------------------------------------------------------------
        */

        $destination = $this->geocodeAddress($address);

        /*
        |--------------------------------------------------------------------------
        | HITUNG JARAK
        |--------------------------------------------------------------------------
        */

        $distanceKm = $this->calculateDistance(
            $this->originLat,
            $this->originLng,
            $destination['lat'],
            $destination['lng']
        );

        /*
        |--------------------------------------------------------------------------
        | PROVIDER
        |--------------------------------------------------------------------------
        */

        $provider = strtolower(trim($provider ?? 'lalamove'));

        if (!in_array($provider, ['lalamove', 'deliveree'])) {
            $provider = 'lalamove';
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG TARIF
        |--------------------------------------------------------------------------
        */

        $fee = $this->getLocalShippingFee(
            $provider,
            $distanceKm
        );

        $courier = $provider === 'lalamove'
            ? 'Lalamove'
            : 'Deliveree';

        $serviceType = $provider === 'lalamove'
            ? 'MOTORCYCLE'
            : 'PICKUP / L300';

        /*
        |--------------------------------------------------------------------------
        | ESTIMASI WAKTU
        |--------------------------------------------------------------------------
        */

        $etaMinutes = $this->estimateEta($distanceKm);

        return [
            'provider' => $courier,
            'courier' => $courier,
            'fee' => $fee,
            'distance_km' => round($distanceKm, 1),
            'eta_minutes' => $etaMinutes,
            'service_type' => $serviceType,

            'all_quotes' => [
                [
                    'provider' => $courier,
                    'courier' => $courier,
                    'fee' => $fee,
                    'distance_km' => round($distanceKm, 1),
                    'eta_minutes' => $etaMinutes,
                    'service_type' => $serviceType,
                ],
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | TARIF BERDASARKAN JARAK
    |--------------------------------------------------------------------------
    */

    private function getLocalShippingFee(
        string $provider,
        float $distanceKm
    ): int {

        $rates = $this->shippingRates[$provider] ?? [];

        foreach ($rates as $rate) {

            if ($distanceKm <= $rate['max_km']) {
                return (int) $rate['fee'];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return $provider === 'lalamove'
            ? 125000
            : 150000;
    }


    /*
    |--------------------------------------------------------------------------
    | ESTIMASI ETA
    |--------------------------------------------------------------------------
    */

    private function estimateEta(float $distanceKm): int
    {
        if ($distanceKm <= 5) {
            return 30;
        }

        if ($distanceKm <= 10) {
            return 45;
        }

        if ($distanceKm <= 20) {
            return 60;
        }

        if ($distanceKm <= 30) {
            return 90;
        }

        if ($distanceKm <= 50) {
            return 120;
        }

        return 180;
    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG JARAK - HAVERSINE
    |--------------------------------------------------------------------------
    */

    private function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {

        $earthRadius = 6371;

        $latDifference = deg2rad($lat2 - $lat1);
        $lngDifference = deg2rad($lng2 - $lng1);

        $a =
            sin($latDifference / 2) ** 2
            +
            cos(deg2rad($lat1))
            *
            cos(deg2rad($lat2))
            *
            sin($lngDifference / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }


    /*
    |--------------------------------------------------------------------------
    | GEOCODING SEDERHANA
    |--------------------------------------------------------------------------
    |
    | Untuk sekarang tidak membutuhkan API.
    |
    */

    private function geocodeAddress(string $address): array
    {
        $addressLower = strtolower($address);

        $locations = [

            /*
            |--------------------------------------------------------------------------
            | BANYUMAS
            |--------------------------------------------------------------------------
            */

            'sokaraja' => [
                'lat' => -7.5167,
                'lng' => 109.1500,
            ],

            'banyumas' => [
                'lat' => -7.5167,
                'lng' => 109.1500,
            ],

            'purwokerto' => [
                'lat' => -7.4246,
                'lng' => 109.2396,
            ],

            'wangon' => [
                'lat' => -7.5344,
                'lng' => 108.7917,
            ],

            'ajibarang' => [
                'lat' => -7.4111,
                'lng' => 108.9833,
            ],

            'kembaran' => [
                'lat' => -7.5300,
                'lng' => 109.3000,
            ],

            'patikraja' => [
                'lat' => -7.4900,
                'lng' => 109.2200,
            ],

            /*
            |--------------------------------------------------------------------------
            | JAWA TENGAH
            |--------------------------------------------------------------------------
            */

            'cilacap' => [
                'lat' => -7.7260,
                'lng' => 109.0090,
            ],

            'purbalingga' => [
                'lat' => -7.3881,
                'lng' => 109.3637,
            ],

            'banjarnegara' => [
                'lat' => -7.4027,
                'lng' => 109.6814,
            ],

            'kebumen' => [
                'lat' => -7.6787,
                'lng' => 109.6576,
            ],

            'kroya' => [
                'lat' => -7.6333,
                'lng' => 109.2500,
            ],

            'gombong' => [
                'lat' => -7.6072,
                'lng' => 109.5142,
            ],

            'magelang' => [
                'lat' => -7.4706,
                'lng' => 110.2177,
            ],

            'semarang' => [
                'lat' => -6.9667,
                'lng' => 110.4167,
            ],

            'solo' => [
                'lat' => -7.5755,
                'lng' => 110.8243,
            ],

            'surakarta' => [
                'lat' => -7.5755,
                'lng' => 110.8243,
            ],

            'yogyakarta' => [
                'lat' => -7.7956,
                'lng' => 110.3695,
            ],

            'jogja' => [
                'lat' => -7.7956,
                'lng' => 110.3695,
            ],

            /*
            |--------------------------------------------------------------------------
            | JAWA BARAT
            |--------------------------------------------------------------------------
            */

            'bandung' => [
                'lat' => -6.9175,
                'lng' => 107.6191,
            ],

            'bogor' => [
                'lat' => -6.5971,
                'lng' => 106.7890,
            ],

            'depok' => [
                'lat' => -6.4025,
                'lng' => 106.7942,
            ],

            'bekasi' => [
                'lat' => -6.2383,
                'lng' => 106.9756,
            ],

            'cirebon' => [
                'lat' => -6.7320,
                'lng' => 108.5523,
            ],

            /*
            |--------------------------------------------------------------------------
            | JABODETABEK
            |--------------------------------------------------------------------------
            */

            'jakarta' => [
                'lat' => -6.2088,
                'lng' => 106.8456,
            ],

            'tangerang' => [
                'lat' => -6.1783,
                'lng' => 106.6319,
            ],

            /*
            |--------------------------------------------------------------------------
            | JAWA TIMUR
            |--------------------------------------------------------------------------
            */

            'surabaya' => [
                'lat' => -7.2575,
                'lng' => 112.7521,
            ],

            /*
            |--------------------------------------------------------------------------
            | BALI
            |--------------------------------------------------------------------------
            */

            'bali' => [
                'lat' => -8.6500,
                'lng' => 115.2167,
            ],
        ];


        foreach ($locations as $city => $coordinates) {

            if (str_contains($addressLower, $city)) {
                return $coordinates;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | LOKASI TIDAK DIKENALI
        |--------------------------------------------------------------------------
        |
        | Jangan mengembalikan Rp0.
        |
        | Kita anggap jarak menengah sehingga pelanggan tetap mendapatkan
        | estimasi ongkir.
        |
        */

        return [
            'lat' => -7.3000,
            'lng' => 109.5000,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE SHIPMENT
    |--------------------------------------------------------------------------
    |
    | Untuk sementara shipment dibuat manual.
    | Nanti bisa dihubungkan ke API ekspedisi.
    |
    */

    public function createShipment(array $orderData): array
    {
        $provider = strtolower(
            $orderData['shipping_provider'] ?? 'lalamove'
        );

        $courier = $provider === 'deliveree'
            ? 'Deliveree'
            : 'Lalamove';

        return [
            'provider' => $courier,
            'tracking_code' => 'AID-' . strtoupper(
                substr(md5(uniqid()), 0, 10)
            ),
            'courier' => $courier,
            'status' => 'pending',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMASI GUDANG
    |--------------------------------------------------------------------------
    */

    public function getOrigin(): array
    {
        return [
            'address' => $this->originAddress,
            'lat' => $this->originLat,
            'lng' => $this->originLng,
        ];
    }
}