<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PaymentType;

class PaymentTypeFactory extends Factory
{
    protected $model = PaymentType::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->word, // Define how you want to generate the 'value' attribute
        ];
    }
}
