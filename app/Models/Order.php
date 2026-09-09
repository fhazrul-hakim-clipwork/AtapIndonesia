<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'customer_name',
        'customer_email',
        'telepon',
        'alamat',
        'payment_method',
        'subtotal',
        'discount',
        'shipping_fee',
        'grand_total',
        'status',
        'courier',
        'tracking_code',
        'snap_token',
        'virtual_account',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}