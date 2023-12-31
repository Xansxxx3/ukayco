<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ShoppingCart extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
{
    return $this->hasMany(ShoppingCartItem::class, 'cart_id');
}
}
