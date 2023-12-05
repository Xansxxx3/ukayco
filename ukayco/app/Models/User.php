<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'date_of_birth',
        'contact_number',
        'gender',
        'email',
        'password',
        'facebook_id',
        'google_id',
        'is_subscribe_to_newsletters',
        'is_subscribe_to_promotions',  
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function routeNotificationForVonage($notification){
        return $this->contact_number;
    }

    public function productItems()
    {
        return $this->hasMany(ProductItem::class);
    }
    public function shoppingCart()
    {
        return $this->hasOne(ShoppingCart::class);
    }
    public function userPaymentMethod()
    {
        return $this->hasOne(userPaymentMethod::class);
    }
    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }

}
