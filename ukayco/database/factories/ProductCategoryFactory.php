<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;


    public function definition(): array
    {
        return [
            'category_name' => $this->faker->word,
            'category_id' => $this->faker->boolean(30) ? $this->faker->randomElement(ProductCategory::pluck('id')->toArray()) : null,

        ];
    }

}
