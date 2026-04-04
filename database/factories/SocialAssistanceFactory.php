<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialAssistance>
 */
class SocialAssistanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail' => $this->faker->imageUrl(),
            'name' => $this->faker->randomElement(['Bantuan Pendidikan', 'Bantuan Pangan', 'Bantuan Kesehatan', 'Bantuan BMG']) . '' . $this->faker->company(),
            'category' => $this->faker->randomElement(['staple', 'cash', 'subsidied fuel', 'health']),
            'amount' => $this->faker->randomElement([
                100000,
                150000,
                200000,
                300000,
                500000,
                1000000,
                1500000,
                2000000,
                5000000,
                10000000,
                25000000,
                50000000,
                100000000,
                150000000,
                200000000,
            ]),
            'provider' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'is_available' => $this->faker->boolean(),
        ];
    }
}
