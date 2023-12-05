<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function __invoke()
    {
        return Product::query()->get();
    }


    public function getVariantsByProductTypes($id)
    {
        $productType = Product::find($id);
        if (!$productType) {
            return response()->json(['error' => 'Product Type not found'], 404);
        }
        $categoryId = $productType->category_id;
        $category = ProductCategory::find($categoryId);
        
        $variants = $category->variations->load('variationOptions');
        
        return $variants;
    }
    
}
