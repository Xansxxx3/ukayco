<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Variation;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';
    
    protected $fillable = ['category_name', 'category_id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(ProductCategory::class, 'category_id');
    }

    public function variations()
    {
        return $this->hasMany(Variation::class, 'category_id');
    }
}
