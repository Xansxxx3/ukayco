<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_number',
        'address_line_1',
        'address_line_2',
        'city',
        'region',
        'postal_code',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }
}
