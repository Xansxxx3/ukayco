<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductConfiguration extends Model
{
    protected $fillable = [
        'product_item_id',
        'variation_option_id',
    ];

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }

    public function variationOption()
    {
        return $this->belongsTo(VariationOption::class, 'variation_option_id');
    }

    
}
