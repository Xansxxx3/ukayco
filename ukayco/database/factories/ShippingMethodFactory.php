<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShippingMethod;

class ShippingMethodFactory extends Factory
{
    protected $model = ShippingMethod::class;

    public function definition()
    {
        return [
            'price' => $this->faker->randomFloat(2, 1, 1000), 
            'name' => $this->faker->word, 
        ];  
    }
}
