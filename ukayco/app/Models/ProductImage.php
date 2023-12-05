<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductItem;
class ProductImage extends Model
{
    use HasFactory;


    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'product_image'
    ];

    public function product()
    {
        return $this->belongsTo(ProductItem::class);
    }
}
