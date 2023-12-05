<?php

namespace Database\Factories;
use App\Models\UserPaymentMethod;
use App\Models\User;
use App\Models\PaymentType;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserPaymentMethodFactory extends Factory
{
    protected $model = UserPaymentMethod::class;

    public function definition()
    {
        return [
        'user_id' => User::pluck('id')->random(),
        'payment_type_id' => PaymentType::pluck('id')->random(),
        'provider' => $this->faker->word,
        'account_number' => $this->faker->creditCardNumber,
        'expiry_date' => $this->faker->date,
        'is_default' => $this->faker->boolean,
        ];
    }
}
