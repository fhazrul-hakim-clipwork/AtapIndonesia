<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\PaymentService;
use App\Services\ShippingService;

class TalangController extends Controller
{
    private ShippingService $shippingService;
    private PaymentService $paymentService;

    public function __construct(
        ShippingService $shippingService,
        PaymentService $paymentService
    ) {
        $this->shippingService = $shippingService;
        $this->paymentService = $paymentService;
    }

    // ===========================================================
    // HALAMAN PUBLIK
    // ===========================================================

    public function landing()
    {
        $data = $this->getKatalogData();

        return view('welcome', compact('data'));
    }

    public function katalog()
    {
        $data = $this->getKatalogData();

        return view('katalog', compact('data'));
    }

    // ===========================================================
    // SISTEM PAKAR (KALKULATOR CERDAS)
    // ===========================================================

    public function hitung(Request $request)
    {
        $isGuest = !auth()->check() && !session('is_logged_in');

        if ($isGuest) {
            $count = session('sistem_pakar_count', 0);

            if ($count >= 3) {
                return redirect()
                    ->route('login')
                    ->with(
                        'sukses',
                        'Anda telah menggunakan 3 kali percobaan gratis. Silakan masuk atau daftar untuk penggunaan tanpa batas.'
                    );
            }

            session([
                'sistem_pakar_count' => $count + 1
            ]);
        }

        $request->validate([
            'panjang_karung' => 'required|numeric|min:1',
            'lebar_bidang'   => 'required|numeric|min:1',
            'curah_hujan'    => 'required|string',
            'material'       => 'required|string',
            'jenis_bangunan' => 'nullable|string',
            'kemiringan'     => 'nullable|string',
            'jumlah_pipa'    => 'nullable|integer|min:1',
        ]);

        $panjang = (float) $request->panjang_karung;
        $lebar = (float) $request->lebar_bidang;
        $hujan = $request->curah_hujan;
        $material = $request->material;

        $jenis_bangunan =
            $request->jenis_bangunan ?? 'Rumah Tinggal';

        $kemiringan =
            $request->kemiringan ?? 'Sedang';

        $jumlah_pipa =
            (int) ($request->jumlah_pipa ?? 2);

        // Intensitas hujan
        $intensitas_mm_jam = match (true) {
            str_contains($hujan, 'Ringan')  => 50,
            str_contains($hujan, 'Sedang') => 100,
            str_contains($hujan, 'Lebat')  => 150,
            str_contains($hujan, 'Sangat') => 200,
            default => 100,
        };

        // Faktor kemiringan
        $faktorKemiringan = match (true) {
            str_contains($kemiringan, 'Landai') => 1.0,
            str_contains($kemiringan, 'Sedang') => 1.2,
            str_contains($kemiringan, 'Curam')  => 1.4,
            default => 1.2,
        };

        // Faktor keamanan
        $safetyFactor = match (true) {
            str_contains($jenis_bangunan, 'Rumah Tinggal') => 1.1,
            str_contains($jenis_bangunan, 'Komersial') => 1.2,
            str_contains($jenis_bangunan, 'Pabrik') => 1.3,
            default => 1.1,
        };

        $luasAtap = $panjang * $lebar;

        $luasEfektif =
            $luasAtap * $faktorKemiringan;

        $debitAir =
            ($intensitas_mm_jam / 3600)
            * $luasEfektif
            * $safetyFactor;

        // Rekomendasi talang
        if ($debitAir < 1.5) {
            $dimensiRekomendasi =
                "Talang Setengah Lingkaran 15 cm / Kotak 12 cm";
        } elseif ($debitAir < 3.5) {
            $dimensiRekomendasi =
                "Talang Setengah Lingkaran 20 cm / Kotak 15 cm";
        } elseif ($debitAir < 6.0) {
            $dimensiRekomendasi =
                "Talang Kotak 20 cm (Heavy Duty)";
        } else {
            $dimensiRekomendasi =
                "Talang Kotak Kustom >25 cm / Industrial";
        }

        // Kebutuhan pipa
        $debitPerPipa =
            $debitAir / max(1, $jumlah_pipa);

        $rekomendasiPipa = $jumlah_pipa;

        if ($debitPerPipa > 2.0) {
            $rekomendasiPipa =
                ceil($debitAir / 2.0);
        }

        // BoQ
        $wasteFactor = 1.05;

        $panjangTalang =
            round($panjang * $wasteFactor, 1);

        $jumlahBracket =
            ceil($panjang / 0.6);

        $jumlahJoint =
            ceil($panjang / 3.0);

        $jumlahCorong =
            $rekomendasiPipa;

        $jumlahEndCap = 2;

        $biaya = $this->estimasiBiaya(
            $material,
            $panjangTalang,
            $jumlahBracket,
            $jumlahJoint,
            $jumlahCorong,
            $jumlahEndCap
        );

        $tingkatResiko = match (true) {
            $intensitas_mm_jam >= 200 || $debitAir > 5
                => 'SANGAT TINGGI',

            $intensitas_mm_jam >= 150 || $debitAir > 3
                => 'TINGGI',

            $intensitas_mm_jam >= 100
                => 'SEDANG',

            default
                => 'RENDAH',
        };

        $confidence = match (true) {
            $intensitas_mm_jam >= 200 => 97,
            $intensitas_mm_jam >= 150 => 94,
            $intensitas_mm_jam >= 100 => 91,
            default => 86,
        };

        $sisaPercobaan = $isGuest
            ? max(
                0,
                3 - session('sistem_pakar_count', 1)
            )
            : null;

        $hasil = [
            'panjang' => $panjang,
            'lebar' => $lebar,
            'kemiringan' => $kemiringan,
            'jenis_bangunan' => $jenis_bangunan,
            'luas_efektif' => round($luasEfektif, 2),
            'curah_hujan' => $hujan,
            'material' => $material,
            'debit_air' => round($debitAir, 2),
            'dimensi_talang' => $dimensiRekomendasi,
            'rekomendasi_pipa' => $rekomendasiPipa,

            'boq' => [
                'panjang_talang' => $panjangTalang,
                'bracket' => $jumlahBracket,
                'joint' => $jumlahJoint,
                'corong' => $jumlahCorong,
                'endcap' => $jumlahEndCap,
            ],

            'biaya' => $biaya,
            'tingkat_resiko' => $tingkatResiko,
            'confidence' => $confidence,
            'sisa_percobaan' => $sisaPercobaan,
        ];

        $data = $this->getKatalogData();

        return view(
            'welcome',
            compact('data', 'hasil')
        );
    }

    private function estimasiBiaya(
        string $material,
        float $panjangTalang,
        int $bracket,
        int $joint,
        int $corong,
        int $endcap
    ): array {
        $hargaPerMeter = match (true) {
            str_contains($material, 'Baja') => 180000,
            str_contains($material, 'PVC') => 85000,
            str_contains($material, 'Galvalum') => 145000,
            str_contains($material, 'Bitumen') => 220000,
            default => 120000,
        };

        $hargaBracket = 15000;
        $hargaJoint = 25000;
        $hargaCorong = 45000;
        $hargaEndCap = 20000;

        $biayaTalang =
            $hargaPerMeter * $panjangTalang;

        $biayaAksesoris =
            ($bracket * $hargaBracket)
            + ($joint * $hargaJoint)
            + ($corong * $hargaCorong)
            + ($endcap * $hargaEndCap);

        $biayaPasang =
            50000 * $panjangTalang;

        return [
            'material_utama' => $biayaTalang,
            'aksesoris' => $biayaAksesoris,
            'pasang' => $biayaPasang,
            'total' =>
                $biayaTalang
                + $biayaAksesoris
                + $biayaPasang,
        ];
    }

    // ===========================================================
    // AUTENTIKASI
    // ===========================================================

    public function showLogin()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (
            !$user ||
            !Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'Email atau password salah!'
                ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        Log::info('Login success', [
            'email' => $user->email,
            'role' => $user->role,
            'redirect' =>
                $user->role === 'admin'
                    ? 'dashboard.admin'
                    : 'dashboard.pembeli',
            'auth_check' => Auth::check(),
            'session_id' => session()->getId(),
        ]);

        return match ($user->role) {
            'admin' =>
                redirect()->route('dashboard.admin'),

            default =>
                redirect()->route('dashboard.pembeli'),
        };
    }

    public function showRegister()
    {
        return view('register');
    }

    public function prosesRegister(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:pembeli',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'kota' => 'Indonesia',
        ]);

        Auth::login($user);

        try {
            Mail::to($user->email)
                ->send(
                    new WelcomeEmail(
                        $user->name,
                        route('login')
                    )
                );
        } catch (\Exception $e) {
            Log::error(
                'Gagal mengirim email selamat datang: '
                . $e->getMessage()
            );
        }

        return redirect()
            ->route('dashboard.pembeli')
            ->with(
                'sukses',
                'Selamat datang ' . $user->name . '!'
            );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('landing')
            ->with(
                'sukses',
                'Anda berhasil keluar.'
            );
    }

    // ===========================================================
    // DASHBOARD
    // ===========================================================

    public function dashboardPembeli()
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'pembeli',
            403
        );

        $data = $this->dashboardStats('pembeli');
        $cartSummary = $this->getCartSummary();

        return view(
            'dashboards.pembeli',
            array_merge(
                $data,
                [
                    'user' => $user,
                    'cartItems' => $cartSummary['items'],
                ]
            )
        );
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'pembeli',
            403
        );

        $request->validate([
            'name' =>
                'required|min:3',

            'email' =>
                'required|email|unique:users,email,' . $user->id,

            'telepon' =>
                'required|min:8',

            'kota' =>
                'required|min:3',

            'alamat' =>
                'required|min:5',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
        ]);

        return redirect()
            ->back()
            ->with(
                'sukses',
                'Profil berhasil diperbarui!'
            );
    }

    public function dashboardAdmin()
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $products =
            Product::orderBy('id')->get();

        $orders =
            Order::orderByDesc('created_at')
                ->paginate(15);

        $stats = [
            'total_orders' =>
                Order::count(),

            'total_revenue' =>
                Order::sum('grand_total'),

            'total_products' =>
                Product::count(),

            'total_customers' =>
                User::where(
                    'role',
                    'pembeli'
                )->count(),
        ];

        return view(
            'dashboards.dashboard_admin',
            compact(
                'products',
                'orders',
                'stats',
                'user'
            )
        );
    }

    // ===========================================================
    // ADMIN PRODUCT MANAGEMENT
    // ===========================================================

    public function adminProducts()
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $products =
            Product::orderBy('id')->get();

        return view(
            'dashboards.admin_products',
            compact(
                'products',
                'user'
            )
        );
    }

    public function adminCreateProduct()
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        return view(
            'dashboards.admin_product_form',
            [
                'user' => $user
            ]
        );
    }

    public function adminEditProduct(Product $product)
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        return view(
            'dashboards.admin_product_form',
            compact(
                'product',
                'user'
            )
        );
    }

    public function adminSaveProduct(
        Request $request,
        ?Product $product = null
    ) {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $request->validate([
            'nama' =>
                'required|string|max:255',

            'harga' =>
                'required|integer|min:0',

            'rating' =>
                'required|numeric|min:0|max:5',

            'kategori_id' =>
                'required|integer|min:1|max:5',

            'material' =>
                'required|string|max:100',

            'stok' =>
                'required|integer|min:0',

            'seller' =>
                'required|string|max:255',

            'promo' =>
                'nullable|string|max:255',

            'best' =>
                'nullable|boolean',

            'image' =>
                'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'nama',
            'harga',
            'rating',
            'kategori_id',
            'material',
            'stok',
            'seller',
            'promo',
        ]);

        $data['best'] =
            $request->has('best');

        if ($request->hasFile('image')) {
            $path =
                $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );

            $data['image'] = $path;

        } elseif (
            $product &&
            $request->filled('image')
        ) {
            $data['image'] =
                $product->image;
        }

        if ($product) {

            $product->update($data);

            return redirect()
                ->route(
                    'admin.products.index'
                )
                ->with(
                    'sukses',
                    'Produk berhasil diperbarui.'
                );
        }

        Product::create($data);

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'sukses',
                'Produk berhasil ditambahkan.'
            );
    }

    public function adminDeleteProduct(
        Product $product
    ) {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $product->delete();

        return back()
            ->with(
                'sukses',
                'Produk berhasil dihapus.'
            );
    }

    // ===========================================================
    // ADMIN INVOICE MANAGEMENT
    // ===========================================================

    public function adminInvoices()
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $orders =
            Order::orderByDesc('created_at')
                ->paginate(20);

        return view(
            'dashboards.admin_invoices',
            compact(
                'orders',
                'user'
            )
        );
    }

    public function adminInvoiceDetail(
        Order $order
    ) {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        return view(
            'dashboards.admin_invoice_detail',
            compact(
                'order',
                'user'
            )
        );
    }

    private function dashboardStats(
        string $role
    ): array {
        if ($role === 'admin') {
            return [
                'stats' => [
                    [
                        'label' => 'Total Order',
                        'value' => Order::count(),
                        'icon' => '📦',
                        'color' => 'orange',
                        'desc' => 'Seluruh pesanan'
                    ],
                    [
                        'label' => 'Revenue Bulan Ini',
                        'value' =>
                            'Rp '
                            . number_format(
                                Order::sum('grand_total'),
                                0,
                                ',',
                                '.'
                            ),
                        'icon' => '💰',
                        'color' => 'emerald',
                        'desc' => 'Total penjualan'
                    ],
                    [
                        'label' => 'Total Produk',
                        'value' => Product::count(),
                        'icon' => '📊',
                        'color' => 'blue',
                        'desc' => 'Katalog aktif'
                    ],
                    [
                        'label' => 'Pembeli',
                        'value' =>
                            User::where(
                                'role',
                                'pembeli'
                            )->count(),
                        'icon' => '👥',
                        'color' => 'purple',
                        'desc' => 'Pengguna terdaftar'
                    ],
                ],
            ];
        }

        $userId = Auth::id();

        $activeOrders =
            Order::where(
                'user_id',
                $userId
            )
                ->whereNotIn(
                    'status',
                    [
                        'selesai',
                        'dibatalkan'
                    ]
                )
                ->count();

        $totalPengeluaran =
            Order::where(
                'user_id',
                $userId
            )
                ->whereNotIn(
                    'status',
                    ['dibatalkan']
                )
                ->sum('grand_total');

        $favoriteProduct =
            \Illuminate\Support\Facades\DB::table(
                'order_items'
            )
                ->join(
                    'orders',
                    'orders.id',
                    '=',
                    'order_items.order_id'
                )
                ->where(
                    'orders.user_id',
                    $userId
                )
                ->select(
                    'product_name',
                    \Illuminate\Support\Facades\DB::raw(
                        'SUM(quantity) as total_qty'
                    )
                )
                ->groupBy(
                    'product_name'
                )
                ->orderByDesc(
                    'total_qty'
                )
                ->first();

        $favoriteProductName =
            $favoriteProduct
                ? \Illuminate\Support\Str::limit(
                    $favoriteProduct->product_name,
                    15
                )
                : 'Belum Ada';

        $favoriteProductCount =
            $favoriteProduct
                ? $favoriteProduct->total_qty
                    . ' item dibeli'
                : '0 pembelian';

        $recentOrdersRaw =
            Order::with('items')
                ->where(
                    'user_id',
                    $userId
                )
                ->orderByDesc(
                    'created_at'
                )
                ->take(5)
                ->get();

        $formattedOrders =
            $recentOrdersRaw
                ->map(function ($order) {

                    $itemsSummary =
                        $order->items
                            ->pluck('product_name')
                            ->implode(', ');

                    return [
                        'id' =>
                            $order->order_id,

                        'item' =>
                            \Illuminate\Support\Str::limit(
                                $itemsSummary,
                                40
                            ),

                        'status' =>
                            $order->status,

                        'snap_token' =>
                            $order->snap_token,

                        'total' =>
                            'Rp '
                            . number_format(
                                $order->grand_total,
                                0,
                                ',',
                                '.'
                            ),
                    ];
                })
                ->toArray();

        return [
            'stats' => [
                [
                    'label' => 'Pesanan Aktif',
                    'value' => $activeOrders,
                    'icon' => '📦',
                    'color' => 'blue',
                    'desc_class' => 'text-blue-500',
                    'desc' => 'Sedang diproses/dikirim'
                ],
                [
                    'label' => 'Total Pengeluaran',
                    'value' =>
                        'Rp '
                        . number_format(
                            $totalPengeluaran,
                            0,
                            ',',
                            '.'
                        ),
                    'icon' => '💰',
                    'color' => 'emerald',
                    'desc_class' => 'text-slate-500',
                    'desc' => 'Sepanjang waktu'
                ],
                [
                    'label' => 'Material Favorit',
                    'value' => $favoriteProductName,
                    'icon' => '⭐',
                    'color' => 'orange',
                    'desc_class' => 'text-slate-500',
                    'desc' => $favoriteProductCount
                ],
                [
                    'label' => 'Toko Favorit',
                    'value' => '3 Toko',
                    'icon' => '🤝',
                    'color' => 'purple',
                    'desc_class' => 'text-orange-500',
                    'desc' => 'Mitra terbaik'
                ],
            ],

            'orders' => $formattedOrders,
        ];
    }

    // ===========================================================
    // KERANJANG & CHECKOUT
    // ===========================================================

    private function getCart(): array
    {
        return Session::get(
            'cart',
            []
        );
    }

    private function saveCart(
        array $cart
    ): void {
        Session::put(
            'cart',
            $cart
        );
    }

    private function findProduct(
        int $productId
    ): ?array {
        return Product::find(
            $productId
        )?->toArray();
    }

    private function getCartSummary(): array
    {
        $cart = $this->getCart();

        $items = [];
        $subtotal = 0;
        $totalItems = 0;

        foreach (
            $cart as $productId => $item
        ) {
            $product =
                $this->findProduct(
                    (int) $productId
                );

            if (!$product) {
                continue;
            }

            $quantity =
                max(
                    1,
                    (int) (
                        $item['quantity'] ?? 1
                    )
                );

            $lineTotal =
                $product['harga']
                * $quantity;

            $subtotal += $lineTotal;
            $totalItems += $quantity;

            $items[] = [
                'id' => $product['id'],
                'nama' => $product['nama'],
                'harga' => $product['harga'],
                'quantity' => $quantity,
                'stok' => $product['stok'],
                'subtotal' => $lineTotal,
            ];
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'totalItems' => $totalItems,
        ];
    }

    public function cartIndex()
    {
        $summary =
            $this->getCartSummary();

        return view(
            'cart',
            [
                'cartItems' =>
                    $summary['items'],

                'total' =>
                    $summary['subtotal'],

                'totalItems' =>
                    $summary['totalItems'],
            ]
        );
    }

    public function checkoutIndex()
    {
        $summary =
            $this->getCartSummary();

        if (
            $summary['totalItems'] === 0
        ) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'sukses',
                    'Keranjang Anda masih kosong.'
                );
        }

        return view(
            'checkout',
            [
                'summary' => $summary,

                'userName' =>
                    Auth::check()
                        ? Auth::user()->name
                        : (
                            Session::get(
                                'user_name'
                            )
                            ?: 'Customer'
                        ),
            ]
        );
    }

    public function processCheckout(
        Request $request
    ) {
        $request->validate([
            'nama' =>
                'required|min:3',

            'email' =>
                'required|email',

            'telepon' =>
                'required|min:8',

            'alamat' =>
                'required|min:5',
        ], [
            'nama.required' =>
                'Nama lengkap wajib diisi.',

            'nama.min' =>
                'Nama lengkap minimal 3 karakter.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'telepon.required' =>
                'Nomor telepon wajib diisi.',

            'telepon.min' =>
                'Nomor telepon minimal 8 karakter.',

            'alamat.required' =>
                'Alamat pengiriman wajib diisi.',

            'alamat.min' =>
                'Alamat pengiriman minimal 5 karakter.',
        ]);

        $summary =
            $this->getCartSummary();

        if (
            $summary['totalItems'] === 0
        ) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'sukses',
                    'Keranjang Anda masih kosong.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DATA ORDER
        |--------------------------------------------------------------------------
        */

        $orderId =
            'TRX-'
            . strtoupper(
                substr(
                    uniqid('', true),
                    -6
                )
            );

        $shippingProvider =
            $request->shipping_provider
            ?? 'lalamove';

        $shippingResult =
            $this->shippingService
                ->calculateShippingFee(
                    $request->alamat,
                    $summary['items'],
                    $shippingProvider
                );

        $shippingFee =
            $shippingResult['fee'];

        $grandTotal =
            $summary['subtotal']
            + $shippingFee;

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI METODE PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $metode =
            $request->metode_pembayaran
            ?? 'va';

        if (
            in_array(
                strtolower($metode),
                [
                    'bca',
                    'bca_va',
                    'virtual_account',
                    'virtual-account',
                    'va',
                ],
                true
            )
        ) {
            $metode = 'va';
        }

        /*
        |--------------------------------------------------------------------------
        | BUAT ORDER
        |--------------------------------------------------------------------------
        */

        $order = Order::create([
            'user_id' =>
                Auth::id(),

            'order_id' =>
                $orderId,

            'customer_name' =>
                $request->nama,

            'customer_email' =>
                $request->email,

            'telepon' =>
                $request->telepon,

            'alamat' =>
                $request->alamat,

            'payment_method' =>
                $metode,

            'subtotal' =>
                $summary['subtotal'],

            'shipping_fee' =>
                $shippingFee,

            'grand_total' =>
                $grandTotal,

            'status' =>
                'menunggu_pembayaran',

            'courier' =>
                $shippingResult['provider']
                ?? (
                    $shippingResult['courier']
                    ?? 'Belum ditentukan'
                ),

            'tracking_code' =>
                null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ORDER ITEMS
        |--------------------------------------------------------------------------
        */

        foreach (
            $summary['items'] as $item
        ) {
            $order->items()->create([
                'product_id' =>
                    $item['id'],

                'product_name' =>
                    $item['nama'],

                'quantity' =>
                    $item['quantity'],

                'price' =>
                    $item['harga'],

                'subtotal' =>
                    $item['subtotal'],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ORDER KE SESSION
        |--------------------------------------------------------------------------
        */

        Session::put(
            'pending_order',
            $order->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | BCA VIRTUAL ACCOUNT SIMULASI
        |--------------------------------------------------------------------------
        |
        | Xendit sudah dihapus.
        | VA sekarang dibuat secara lokal melalui PaymentService.
        |
        */

        if ($metode === 'va') {

            try {

                $this->paymentService
                    ->createBcaVirtualAccount(
                        $order
                    );

                $order->refresh();

                Session::put(
                    'pending_order',
                    $order->toArray()
                );

                Session::forget('cart');

                return redirect()
                    ->route(
                        'orders.show',
                        $order->order_id
                    )
                    ->with(
                        'sukses',
                        'Pesanan berhasil dibuat. Silakan lakukan pembayaran menggunakan Virtual Account.'
                    );

            } catch (\Exception $e) {

                Log::error(
                    'BCA Virtual Account Error',
                    [
                        'order_id' =>
                            $order->order_id,

                        'message' =>
                            $e->getMessage(),
                    ]
                );

                return redirect()
                    ->route(
                        'orders.show',
                        $order->order_id
                    )
                    ->with(
                        'error',
                        'Pesanan berhasil dibuat, tetapi Virtual Account gagal dibuat.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSFER BANK MANUAL
        |--------------------------------------------------------------------------
        */

        if ($metode === 'bank') {

            Session::forget('cart');

            return redirect()
                ->route(
                    'orders.show',
                    $order->order_id
                )
                ->with(
                    'sukses',
                    'Pesanan berhasil dibuat. Silakan lakukan pembayaran sesuai informasi transfer.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | METODE PEMBAYARAN LAIN
        |--------------------------------------------------------------------------
        |
        | Tidak ada lagi pemrosesan Xendit.
        |
        */

        Session::forget('cart');

        return redirect()
            ->route(
                'orders.show',
                $order->order_id
            )
            ->with(
                'sukses',
                'Pesanan berhasil dibuat. Silakan lakukan pembayaran.'
            );
    }

    public function paymentPage()
    {
        $orderFromSession =
            Session::get(
                'pending_order'
            );

        if (!$orderFromSession) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'sukses',
                    'Belum ada pesanan yang bisa dibayar.'
                );
        }

        $order =
            Order::where(
                'order_id',
                $orderFromSession['order_id']
            )->firstOrFail();

        return view(
            'payment',
            [
                'order' => $order,
            ]
        );
    }

    public function shippingPage()
    {
        $orderFromSession =
            Session::get(
                'pending_order'
            );

        if (!$orderFromSession) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'sukses',
                    'Belum ada pesanan yang bisa dilacak.'
                );
        }

        $order =
            Order::where(
                'order_id',
                $orderFromSession['order_id']
            )->firstOrFail();

        $statusKey =
            $order->status;

        $shippingStatus =
            match ($statusKey) {
                'dibayar' =>
                    'Pesanan Dibayar',

                'dikemas' =>
                    'Sedang Dikemas',

                'dikirim' =>
                    'Dalam Perjalanan',

                'selesai' =>
                    'Telah Tiba',

                default =>
                    'Menunggu Pembayaran',
            };

        $tracking = [
            'status' =>
                $shippingStatus,

            'step' =>
                $statusKey,

            'estimated_days' =>
                2,

            'courier' =>
                $order->courier
                ?? 'Belum ada',

            'tracking_code' =>
                $order->tracking_code
                ?? 'Belum tersedia',

            'address' =>
                $order->alamat,

            'eta' =>
                now()
                    ->addDays(2)
                    ->locale('id')
                    ->translatedFormat(
                        'd F Y'
                    ),
        ];

        return view(
            'shipping',
            [
                'pendingOrder' =>
                    $order->toArray(),

                'tracking' =>
                    $tracking,
            ]
        );
    }

    public function showOrder(
        $order_id
    ) {
        $order =
            Order::with('items')
                ->where(
                    'order_id',
                    $order_id
                )
                ->firstOrFail();

        return view(
            'orders.show',
            compact('order')
        );
    }

    // ===========================================================
    // KERANJANG ACTIONS
    // ===========================================================

    public function addToCart(
        Request $request
    ) {
        $request->validate([
            'product_id' =>
                'required|integer',

            'quantity' =>
                'nullable|integer|min:1',
        ]);

        $productId =
            (int) $request->product_id;

        $product =
            $this->findProduct(
                $productId
            );

        if (!$product) {
            return redirect()
                ->back()
                ->with(
                    'sukses',
                    'Produk tidak ditemukan.'
                );
        }

        $quantity =
            max(
                1,
                (int) (
                    $request->quantity ?? 1
                )
            );

        $cart =
            $this->getCart();

        $existingQty =
            isset($cart[$productId])
                ? (int) $cart[$productId]['quantity']
                : 0;

        $newQty =
            min(
                $product['stok'],
                $existingQty + $quantity
            );

        $cart[$productId] = [
            'product_id' =>
                $productId,

            'quantity' =>
                $newQty,
        ];

        $this->saveCart($cart);

        $cartSummary =
            $this->getCartSummary();

        $cartCount =
            $cartSummary['totalItems'];

        if (
            $request->ajax()
            || $request->wantsJson()
        ) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' =>
                    $product['nama']
                    . ' berhasil ditambahkan ke keranjang.',
                'cart_count' =>
                    $cartCount,
                'totalItems' =>
                    $cartSummary['totalItems'],
            ]);
        }

        return back()
            ->with(
                'sukses',
                $product['nama']
                . ' berhasil ditambahkan ke keranjang.'
            );
    }

    public function buyNow(
        Request $request
    ) {
        $request->validate([
            'product_id' =>
                'required|integer',

            'quantity' =>
                'nullable|integer|min:1',
        ]);

        $productId =
            (int) $request->product_id;

        $product =
            $this->findProduct(
                $productId
            );

        if (!$product) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Produk tidak ditemukan.'
                );
        }

        $stok =
            (int) (
                $product['stok'] ?? 0
            );

        if ($stok <= 0) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Produk sedang habis.'
                );
        }

        $quantity =
            max(
                1,
                (int) (
                    $request->quantity ?? 1
                )
            );

        $quantity =
            min(
                $stok,
                $quantity
            );

        $this->saveCart([
            $productId => [
                'product_id' =>
                    $productId,

                'quantity' =>
                    $quantity,
            ],
        ]);

        return redirect()
            ->route(
                'checkout.index'
            );
    }

    public function updateCart(
        Request $request
    ) {
        $request->validate([
            'product_id' =>
                'required|integer',

            'quantity' =>
                'nullable|integer|min:1',

            'delta' =>
                'nullable|integer',
        ]);

        $productId =
            (int) $request->product_id;

        $product =
            $this->findProduct(
                $productId
            );

        if (!$product) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'sukses',
                    'Produk tidak tersedia.'
                );
        }

        $cart =
            $this->getCart();

        if (
            !isset($cart[$productId])
        ) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'sukses',
                    'Item tidak ada di keranjang.'
                );
        }

        $currentQuantity =
            max(
                1,
                (int) (
                    $cart[$productId]['quantity']
                    ?? 1
                )
            );

        $delta =
            (int) (
                $request->delta ?? 0
            );

        if (
            $request->has('quantity')
        ) {
            $quantity =
                max(
                    1,
                    (int) $request->quantity
                );
        } else {
            $quantity =
                $currentQuantity + $delta;
        }

        $quantity =
            max(
                1,
                min(
                    $product['stok'],
                    $quantity
                )
            );

        $cart[$productId]['quantity'] =
            $quantity;

        $this->saveCart($cart);

        return redirect()
            ->route('cart.index')
            ->with(
                'sukses',
                'Jumlah keranjang diperbarui.'
            );
    }

    public function removeCart(
        Request $request
    ) {
        $request->validate([
            'product_id' =>
                'required|integer'
        ]);

        $productId =
            (int) $request->product_id;

        $cart =
            $this->getCart();

        if (
            isset($cart[$productId])
        ) {
            unset(
                $cart[$productId]
            );

            $this->saveCart($cart);
        }

        return redirect()
            ->route('cart.index')
            ->with(
                'sukses',
                'Item dihapus dari keranjang.'
            );
    }

    public function clearCart()
    {
        Session::forget('cart');

        return redirect()
            ->route('cart.index')
            ->with(
                'sukses',
                'Keranjang dibersihkan.'
            );
    }

    // ===========================================================
    // DATA KATALOG TERPUSAT
    // ===========================================================

    private function getKatalogData(): array
    {
        $kategori = [
            [
                'id' => 1,
                'nama' => 'Sepatu Safety Boot',
                'slug' => 'sepatu-safety-boot',
                'icon' => '🥾',
                'count' => 0
            ],
            [
                'id' => 2,
                'nama' => 'Pipa PVC',
                'slug' => 'pipa-pvc',
                'icon' => '🚰',
                'count' => 0
            ],
            [
                'id' => 3,
                'nama' => 'Atap',
                'slug' => 'atap',
                'icon' => '🏠',
                'count' => 0
            ],
            [
                'id' => 4,
                'nama' => 'Karpet Talang',
                'slug' => 'karpet-talang',
                'icon' => '📜',
                'count' => 0
            ],
            [
                'id' => 5,
                'nama' => 'Selang',
                'slug' => 'selang',
                'icon' => '💦',
                'count' => 0
            ],
        ];

        $produk =
            Product::all()->toArray();

        $counts =
            array_count_values(
                array_column(
                    $produk,
                    'kategori_id'
                )
            );

        foreach (
            $kategori as &$item
        ) {
            $item['count'] =
                $counts[$item['id']] ?? 0;
        }

        return [
            'kategori' => $kategori,
            'produk' => $produk,
        ];
    }

    // ===========================================================
    // ADMIN ORDER MANAGEMENT
    // ===========================================================

    public function adminOrderList()
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $orders =
            Order::orderByDesc(
                'created_at'
            )->paginate(20);

        return view(
            'dashboards.admin_orders',
            compact(
                'orders',
                'user'
            )
        );
    }

    public function adminOrderDetail(
        Order $order
    ) {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        return view(
            'dashboards.admin_order_detail',
            compact(
                'order',
                'user'
            )
        );
    }

    public function adminUpdateShipping(
        Request $request,
        Order $order
    ) {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $request->validate([
            'courier' =>
                'required|string|max:255',

            'tracking_code' =>
                'required|string|max:255',

            'status' =>
                'required|in:menunggu_pembayaran,dibayar,dikemas,dikirim,selesai,dibatalkan',

            'payment_method' =>
                'nullable|string|in:qris,bank,va',
        ]);

        $order->update([
            'courier' =>
                $request->courier,

            'tracking_code' =>
                $request->tracking_code,

            'status' =>
                $request->status,

            'payment_method' =>
                $request->payment_method
                ?? $order->payment_method,
        ]);

        return back()
            ->with(
                'sukses',
                'Status pengiriman dan pembayaran berhasil diperbarui.'
            );
    }

    // ===========================================================
    // ADMIN CUSTOMER MANAGEMENT
    // ===========================================================

    public function adminCustomers()
    {
        $user = Auth::user();

        abort_if(
            $user->role !== 'admin',
            403
        );

        $customers =
            User::where(
                'role',
                'pembeli'
            )
                ->orderBy(
                    'created_at',
                    'desc'
                )
                ->paginate(20);

        return view(
            'dashboards.admin_customers',
            compact(
                'customers',
                'user'
            )
        );
    }

    public function adminCustomerDetail(
        User $user
    ) {
        $admin = Auth::user();

        abort_if(
            $admin->role !== 'admin',
            403
        );

        $orders =
            Order::where(
                'customer_email',
                $user->email
            )
                ->orderByDesc(
                    'created_at'
                )
                ->get();

        return view(
            'dashboards.admin_customer_detail',
            compact(
                'user',
                'admin',
                'orders'
            )
        );
    }

    public function adminCustomerEdit(
        User $user
    ) {
        $admin = Auth::user();

        abort_if(
            $admin->role !== 'admin',
            403
        );

        return view(
            'dashboards.admin_customer_edit',
            compact(
                'user',
                'admin'
            )
        );
    }

    public function adminCustomerUpdate(
        Request $request,
        User $user
    ) {
        $admin = Auth::user();

        abort_if(
            $admin->role !== 'admin',
            403
        );

        $request->validate([
            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|max:255|unique:users,email,'
                . $user->id,

            'telepon' =>
                'nullable|string|max:20',

            'kota' =>
                'nullable|string|max:255',

            'alamat' =>
                'nullable|string',
        ]);

        $user->update([
            'name' =>
                $request->name,

            'email' =>
                $request->email,

            'telepon' =>
                $request->telepon,

            'kota' =>
                $request->kota,

            'alamat' =>
                $request->alamat,
        ]);

        return redirect()
            ->route(
                'admin.customers.index'
            )
            ->with(
                'sukses',
                'Data pelanggan berhasil diperbarui.'
            );
    }

    public function adminCustomerDestroy(
        User $user
    ) {
        $admin = Auth::user();

        abort_if(
            $admin->role !== 'admin',
            403
        );

        $user->delete();

        return redirect()
            ->route(
                'admin.customers.index'
            )
            ->with(
                'sukses',
                'Pelanggan berhasil dihapus.'
            );
    }

    // ===========================================================
    // TICKETING SYSTEM
    // ===========================================================

    public function showTickets()
    {
        $userRole =
            Auth::check()
                ? Auth::user()->role
                : Session::get(
                    'user_role'
                );

        $userEmail =
            Auth::check()
                ? Auth::user()->email
                : Session::get(
                    'user_email'
                );

        if ($userRole === 'admin') {

            $tickets =
                Ticket::withCount(
                    'replies'
                )
                    ->orderBy(
                        'status',
                        'asc'
                    )
                    ->orderBy(
                        'updated_at',
                        'desc'
                    )
                    ->get();

        } else {

            $tickets =
                Ticket::where(
                    'user_email',
                    $userEmail
                )
                    ->orderBy(
                        'updated_at',
                        'desc'
                    )
                    ->get();
        }

        return view(
            'dashboards.tickets.index',
            compact('tickets')
        );
    }

    public function showTicketDetail(
        Ticket $ticket
    ) {
        $userEmail =
            Auth::check()
                ? Auth::user()->email
                : Session::get(
                    'user_email'
                );

        $userRole =
            Auth::check()
                ? Auth::user()->role
                : Session::get(
                    'user_role'
                );

        if (
            $userRole !== 'admin'
            && $userEmail !== $ticket->user_email
        ) {
            abort(
                403,
                'Akses Ditolak'
            );
        }

        return view(
            'dashboards.tickets.show',
            compact('ticket')
        );
    }

    public function storeTicket(
        Request $request
    ) {
        $request->validate([
            'subject' =>
                'required|string|max:255',

            'message' =>
                'required|string|min:10',
        ]);

        $user =
            Auth::user();

        $userEmail =
            $user
                ? $user->email
                : Session::get(
                    'user_email'
                );

        $userName =
            $user
                ? $user->name
                : Session::get(
                    'user_name'
                );

        Ticket::create([
            'user_email' =>
                $userEmail,

            'user_name' =>
                $userName,

            'subject' =>
                $request->subject,

            'message' =>
                $request->message,

            'status' =>
                'open',
        ]);

        return redirect()
            ->route('tickets.index')
            ->with(
                'sukses',
                'Pengaduan Anda telah dikirim. Admin akan segera merespon.'
            );
    }

    public function storeTicketReply(
        Request $request,
        Ticket $ticket
    ) {
        $request->validate([
            'message' =>
                'required|string|min:5'
        ]);

        $user =
            Auth::user();

        $replierName =
            $user
                ? $user->name
                : Session::get(
                    'user_name'
                );

        $replierRole =
            $user
                ? $user->role
                : Session::get(
                    'user_role'
                );

        $ticket->replies()->create([
            'replier_role' =>
                $replierRole,

            'replier_name' =>
                $replierName,

            'message' =>
                $request->message,
        ]);

        if (
            $replierRole === 'admin'
        ) {
            $ticket->update([
                'status' => 'answered'
            ]);
        } else {
            $ticket->update([
                'status' => 'open'
            ]);
        }

        return redirect()
            ->route(
                'tickets.show',
                $ticket
            )
            ->with(
                'sukses',
                'Balasan berhasil dikirim.'
            );
    }

    public function closeTicket(
        Ticket $ticket
    ) {
        $userRole =
            Auth::check()
                ? Auth::user()->role
                : Session::get(
                    'user_role'
                );

        abort_if(
            $userRole !== 'admin',
            403
        );

        $ticket->update([
            'status' => 'closed'
        ]);

        return redirect()
            ->route('tickets.index')
            ->with(
                'sukses',
                "Tiket #{$ticket->id} telah ditutup."
            );
    }

    // ===========================================================
    // API METHODS
    // ===========================================================

    public function apiProducts()
    {
        $products =
            Product::all()->map(
                fn($p) => [
                    'id' =>
                        $p->id,

                    'nama' =>
                        $p->nama,

                    'harga' =>
                        $p->harga,

                    'rating' =>
                        $p->rating,

                    'material' =>
                        $p->material,

                    'stok' =>
                        $p->stok,

                    'seller' =>
                        $p->seller,

                    'promo' =>
                        $p->promo,
                ]
            );

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function apiOrders()
    {
        $user =
            Auth::user();

        $query =
            Order::query();

        if (
            $user->role !== 'admin'
        ) {
            $query->where(
                'customer_email',
                $user->email
            );
        }

        $orders =
            $query
                ->orderByDesc(
                    'created_at'
                )
                ->get()
                ->map(
                    fn($o) => [
                        'order_id' =>
                            $o->order_id,

                        'customer_name' =>
                            $o->customer_name,

                        'status' =>
                            $o->status,

                        'grand_total' =>
                            $o->grand_total,

                        'created_at' =>
                            $o->created_at,
                    ]
                );

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function apiShowOrder(
        Order $order
    ) {
        $user =
            Auth::user();

        if (
            $user->role !== 'admin'
            && $order->customer_email
                !== $user->email
        ) {
            abort(403);
        }

        return response()->json([
            'success' => true,
            'data' =>
                $order->load('items'),
        ]);
    }

    public function apiCreateOrder(
        Request $request
    ) {
        $request->validate([
            'nama' =>
                'required|min:3',

            'email' =>
                'required|email',

            'telepon' =>
                'required|min:8',

            'alamat' =>
                'required|min:8',

            'items' =>
                'required|array|min:1',

            'items.*.product_id' =>
                'required|integer|exists:products,id',

            'items.*.quantity' =>
                'required|integer|min:1',
        ]);

        $subtotal = 0;

        foreach (
            $request->items as $item
        ) {
            $product =
                Product::find(
                    $item['product_id']
                );

            if (!$product) {
                return response()->json(
                    [
                        'success' => false,
                        'message' =>
                            'Produk tidak ditemukan'
                    ],
                    404
                );
            }

            $subtotal +=
                $product->harga
                * $item['quantity'];
        }

        $shippingResult =
            $this->shippingService
                ->calculateShippingFee(
                    $request->alamat,
                    $request->items
                );

        $shippingFee =
            $shippingResult['fee'];

        $grandTotal =
            $subtotal
            + $shippingFee;

        $orderId =
            'TRX-'
            . strtoupper(
                substr(
                    uniqid('', true),
                    -6
                )
            );

        $metode =
            $request->metode_pembayaran
            ?? 'va';

        if (
            in_array(
                strtolower($metode),
                [
                    'bca',
                    'bca_va',
                    'virtual_account',
                    'virtual-account',
                    'va',
                ],
                true
            )
        ) {
            $metode = 'va';
        }

        $order = Order::create([
            'user_id' =>
                Auth::id(),

            'order_id' =>
                $orderId,

            'customer_name' =>
                $request->nama,

            'customer_email' =>
                $request->email,

            'telepon' =>
                $request->telepon,

            'alamat' =>
                $request->alamat,

            'payment_method' =>
                $metode,

            'subtotal' =>
                $subtotal,

            'shipping_fee' =>
                $shippingFee,

            'grand_total' =>
                $grandTotal,

            'status' =>
                'menunggu_pembayaran',

            'courier' =>
                $shippingResult['provider']
                ?? (
                    $shippingResult['courier']
                    ?? 'Belum ditentukan'
                ),
        ]);

        foreach (
            $request->items as $item
        ) {
            $product =
                Product::find(
                    $item['product_id']
                );

            $order->items()->create([
                'product_id' =>
                    $product->id,

                'product_name' =>
                    $product->nama,

                'quantity' =>
                    $item['quantity'],

                'price' =>
                    $product->harga,

                'subtotal' =>
                    $product->harga
                    * $item['quantity'],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BCA VA SIMULASI UNTUK API ORDER
        |--------------------------------------------------------------------------
        */

        if (
            $metode === 'va'
        ) {
            try {

                $this->paymentService
                    ->createBcaVirtualAccount(
                        $order
                    );

                $order->refresh();

            } catch (\Exception $e) {

                Log::error(
                    'API Order - BCA VA Error',
                    [
                        'order_id' =>
                            $order->order_id,

                        'message' =>
                            $e->getMessage(),
                    ]
                );

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Order berhasil dibuat tetapi Virtual Account gagal dibuat.',
                    'order_id' =>
                        $order->order_id,
                ], 500);
            }

            return response()->json([
                'success' => true,

                'data' => [
                    'order_id' =>
                        $order->order_id,

                    'grand_total' =>
                        $order->grand_total,

                    'payment_method' =>
                        $order->payment_method,

                    'virtual_account' =>
                        $order->virtual_account,

                    'shipping' =>
                        $shippingResult,
                ],
            ], 201);
        }

        /*
        |--------------------------------------------------------------------------
        | METODE PEMBAYARAN NON-VA
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'data' => [
                'order_id' =>
                    $order->order_id,

                'grand_total' =>
                    $order->grand_total,

                'payment_method' =>
                    $order->payment_method,

                'virtual_account' =>
                    $order->virtual_account,

                'shipping' =>
                    $shippingResult,
            ],
        ], 201);
    }

    public function apiCalculateShipping(
        Request $request
    ) {
        $request->validate([
            'alamat' =>
                'required|string|min:8',

            'items' =>
                'nullable|array',

            'shipping_provider' =>
                'nullable|string|in:lalamove,deliveree',
        ]);

        $result =
            $this->shippingService
                ->calculateShippingFee(
                    $request->alamat,
                    $request->items ?? []
                );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}