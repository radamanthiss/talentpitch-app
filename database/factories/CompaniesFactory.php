<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Companies>
 */
class CompaniesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'image_path' => $this->faker->imageUrl,
            'location' => $this->faker->city,
            'industry' => $this->faker->word,
            'user_id' => User::factory(), // Assuming you have a User factory
        ];
    }
}
