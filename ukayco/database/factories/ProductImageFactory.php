<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
        $productId = Product::pluck('id')->random();
        return [
            'product_id' => $productId,
            'product_image' => $this->faker->imageUrl(640, 480), // Generate a placeholder image URL
        ];
    }
}
