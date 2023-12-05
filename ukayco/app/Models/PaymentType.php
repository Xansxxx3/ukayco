<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $table = 'payment_type'; // Specify the table name if it's not following Laravel's naming conventions

    protected $fillable = [
        'value',
    ];
}
