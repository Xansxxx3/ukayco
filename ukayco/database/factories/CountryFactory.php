<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CountryFactory extends Factory
{
    protected $model = Country::class;


    public function definition(): array
    {
        $country = $this->faker->country;
        $code = strtoupper(substr($country, 0, 2));
        return [
            'name' => $country,
            'code' => $code,
        ];
    }

}
