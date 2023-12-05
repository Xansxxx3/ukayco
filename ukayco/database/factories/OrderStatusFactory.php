<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrderStatus;
class OrderStatusFactory extends Factory
{
    
    protected $model = OrderStatus::class;     

    public function definition()
    {
        return [
            'status' => $this->faker->word,
        ];
    }
}
