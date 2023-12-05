<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $table = 'order_line'; // Set the table name

    protected $fillable = [
        'user_id',
        'product_item_id',
        'price',
        'SKU',
        'order_status_id',
        'payment_method_id',
        'shipping_address_id',
        'shipping_method_id',
        'order_date',
    ];



public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function paymentMethod()
{
    return $this->belongsTo(UserPaymentMethod::class, 'payment_method_id');
}

public function shippingAddress()
{
    return $this->belongsTo(Address::class, 'shipping_address_id');
}

public function orderStatus()
{
    return $this->belongsTo(OrderStatus::class, 'order_status_id');
}

public function shippingMethod()
{
    return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
}

public function productItem()
{
    return $this->belongsTo(ProductItem::class, 'product_item_id');
}
}

