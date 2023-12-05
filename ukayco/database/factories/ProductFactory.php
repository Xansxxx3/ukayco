<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'category_id' => rand(1, 10), // Assuming category IDs from 1 to 10
            'description' => $this->faker->paragraph,
            'product_image' => $this->faker->imageUrl(640, 480, 'products', true),
        ];
    }

}
