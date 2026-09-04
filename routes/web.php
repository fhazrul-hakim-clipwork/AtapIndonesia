<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TalangController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Middleware\EnsureUserIsAuthenticated;


/*
|--------------------------------------------------------------------------
| WEB ROUTES - ATAP INDONESIA
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [TalangController::class, 'landing'])
    ->name('landing');

// Katalog Produk
Route::get('/katalog', [TalangController::class, 'katalog'])
    ->name('katalog');

// Keranjang
Route::get('/keranjang', [TalangController::class, 'cartIndex'])
    ->name('cart.index');

Route::post('/keranjang/tambah', [TalangController::class, 'addToCart'])
    ->name('cart.add');

Route::post('/keranjang/beli-sekarang', [TalangController::class, 'buyNow'])
    ->name('cart.buy_now');

Route::post('/keranjang/update', [TalangController::class, 'updateCart'])
    ->name('cart.update');

Route::post('/keranjang/hapus', [TalangController::class, 'removeCart'])
    ->name('cart.remove');

Route::post('/keranjang/bersihkan', [TalangController::class, 'clearCart'])
    ->name('cart.clear');


/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

// Halaman Checkout
Route::get('/checkout', [TalangController::class, 'checkoutIndex'])
    ->name('checkout.index');

// Proses Checkout
Route::post('/checkout/proses', [TalangController::class, 'processCheckout'])
    ->name('checkout.process');

// Halaman Pembayaran
Route::get('/checkout/pembayaran', [TalangController::class, 'paymentPage'])
    ->name('checkout.payment');

// Halaman Shipping
Route::get('/checkout/shipping', [TalangController::class, 'shippingPage'])
    ->name('checkout.shipping');


/*
|--------------------------------------------------------------------------
| DETAIL PESANAN
|--------------------------------------------------------------------------
*/

// Contoh:
// /pesanan/TRX-915887
Route::get('/pesanan/{order_id}', [TalangController::class, 'showOrder'])
    ->name('orders.show');


/*
|--------------------------------------------------------------------------
| SISTEM PAKAR
|--------------------------------------------------------------------------
*/

Route::post('/talang/hitung', [TalangController::class, 'hitung'])
    ->name('talang.hitung');


/*
|--------------------------------------------------------------------------
| AUTENTIKASI
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [TalangController::class, 'showLogin'])
    ->name('login');

Route::post('/login/proses', [TalangController::class, 'prosesLogin'])
    ->name('login.proses');

// Register
Route::get('/register', [TalangController::class, 'showRegister'])
    ->name('register');

Route::post('/register/proses', [TalangController::class, 'prosesRegister'])
    ->name('register.proses');

// Logout
Route::get('/logout', [TalangController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| GOOGLE OAUTH
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('auth.google');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
|
| Semua route di bawah ini membutuhkan user yang sudah login.
|
*/

Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PEMBELI
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/pembeli', [TalangController::class, 'dashboardPembeli'])
        ->name('dashboard.pembeli');

    Route::post('/dashboard/pembeli/profil', [TalangController::class, 'updateProfile'])
        ->name('pembeli.update_profile');


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/admin', [TalangController::class, 'dashboardAdmin'])
        ->name('dashboard.admin');


    /*
    |--------------------------------------------------------------------------
    | TICKETS
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/tickets', [TalangController::class, 'showTickets'])
        ->name('tickets.index');

    Route::get('/dashboard/tickets/{ticket}', [TalangController::class, 'showTicketDetail'])
        ->name('tickets.show');

    Route::post('/dashboard/tickets', [TalangController::class, 'storeTicket'])
        ->name('tickets.store');

    Route::post('/dashboard/tickets/{ticket}/reply', [TalangController::class, 'storeTicketReply'])
        ->name('tickets.reply');

    Route::post('/dashboard/tickets/{ticket}/close', [TalangController::class, 'closeTicket'])
        ->name('tickets.close');


    /*
    |--------------------------------------------------------------------------
    | ADMIN - ORDER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/admin/orders', [TalangController::class, 'adminOrderList'])
        ->name('admin.orders.index');

    Route::get('/dashboard/admin/orders/{order}', [TalangController::class, 'adminOrderDetail'])
        ->name('admin.orders.show');

    Route::post('/dashboard/admin/orders/{order}/update-shipping', [TalangController::class, 'adminUpdateShipping'])
        ->name('admin.orders.update_shipping');


    /*
    |--------------------------------------------------------------------------
    | ADMIN - PRODUCT MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/admin/products', [TalangController::class, 'adminProducts'])
        ->name('admin.products.index');

    Route::get('/dashboard/admin/products/create', [TalangController::class, 'adminCreateProduct'])
        ->name('admin.products.create');

    Route::get('/dashboard/admin/products/{product}/edit', [TalangController::class, 'adminEditProduct'])
        ->name('admin.products.edit');

    Route::post('/dashboard/admin/products', [TalangController::class, 'adminSaveProduct'])
        ->name('admin.products.store');

    Route::post('/dashboard/admin/products/{product}', [TalangController::class, 'adminSaveProduct'])
        ->name('admin.products.update');

    Route::post('/dashboard/admin/products/{product}/delete', [TalangController::class, 'adminDeleteProduct'])
        ->name('admin.products.destroy');


    /*
    |--------------------------------------------------------------------------
    | ADMIN - INVOICE MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/admin/invoices', [TalangController::class, 'adminInvoices'])
        ->name('admin.invoices.index');

    Route::get('/dashboard/admin/invoices/{order}', [TalangController::class, 'adminInvoiceDetail'])
        ->name('admin.invoices.show');


    /*
    |--------------------------------------------------------------------------
    | ADMIN - CUSTOMER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/admin/customers', [TalangController::class, 'adminCustomers'])
        ->name('admin.customers.index');

    Route::get('/dashboard/admin/customers/{user}', [TalangController::class, 'adminCustomerDetail'])
        ->name('admin.customers.show');

    Route::get('/dashboard/admin/customers/{user}/edit', [TalangController::class, 'adminCustomerEdit'])
        ->name('admin.customers.edit');

    Route::post('/dashboard/admin/customers/{user}', [TalangController::class, 'adminCustomerUpdate'])
        ->name('admin.customers.update');

    Route::post('/dashboard/admin/customers/{user}/delete', [TalangController::class, 'adminCustomerDestroy'])
        ->name('admin.customers.destroy');

});


/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | API YANG MEMBUTUHKAN LOGIN
    |--------------------------------------------------------------------------
    */

    Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {


        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        Route::get('/products', [TalangController::class, 'apiProducts'])
            ->name('api.products');


        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [TalangController::class, 'apiOrders'])
            ->name('api.orders');

        Route::post('/orders', [TalangController::class, 'apiCreateOrder'])
            ->name('api.orders.create');

        Route::get('/orders/{order}', [TalangController::class, 'apiShowOrder'])
            ->name('api.orders.show');


        /*
        |--------------------------------------------------------------------------
        | SHIPPING CALCULATION
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | Checkout menggunakan fetch() dengan method POST.
        |
        */

        Route::post('/shipping/calculate', [TalangController::class, 'apiCalculateShipping'])
            ->name('api.shipping.calculate');

    });

});