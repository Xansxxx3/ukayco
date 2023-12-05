<?php

namespace App\Http\Controllers;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductItem;


class ProductCategoryController extends Controller
{
    public function __invoke()
{
    $categories = ProductCategory::query()->get();

    $organizedCategories = [];

    foreach ($categories as $category) {
        if (!$category->category_id) {
            $organizedCategories[$category->id] = [
                'id' => $category->id,
                'category_id' => $category->category_id, // Include category_id
                'category_name' => $category->category_name,
                'children' => [],
            ];
        } else {
            if (!isset($organizedCategories[$category->category_id])) {
                $organizedCategories[$category->category_id] = [
                    'id' => $category->category_id,
                    'children' => [],
                ];
            }
            $organizedCategories[$category->category_id]['children'][] = [
                'id' => $category->id,
                'category_id' => $category->category_id, // Include category_id
                'category_name' => $category->category_name,
            ];
        }
    }

    $result = array_values($organizedCategories);
    
    return response()->json(['message' => 'success',
                             'data' => $result], 200);
}

    public function show($id)
    {
        // Find the ProductCategory by its ID
        $productCategory = ProductCategory::find($id);
        if (!$productCategory) {
            return response()->json(['message' => 'Product category not found'], 404);
        }

        // Retrieve the variations using the variations relationship
        $variations = $productCategory->variations;

        // Return the product category with its variations in the response
        return response()->json([
            'variations' => $variations,
        ], 200);
    }

    public function getProductItemsByCategory($id)
    {
        $productCategory = ProductCategory::find($id);

        if (!$productCategory) {
            return response()->json(['message' => 'Product category not found'], 404);
        }

        $productItems = Product::where('category_id', $productCategory->id)
            ->with(['productItems', 'productItems.variationOptions', 'productItems.productImages', 'productItems.product'])            ->get()
            ->flatMap(function ($product) {
                return $product->productItems;
            });

        return $productItems;
    }

    public function getProductTypesByCategory($id)
{
    $productCategory = ProductCategory::find($id);
    if (!$productCategory) {
        return response()->json(['message' => 'No Product Types in this Category'], 404);
    }

    $product_types = $productCategory->products;
    return response()->json([
        'message' => 'success',
        'data' => $product_types,
    ], 200);
}
}
