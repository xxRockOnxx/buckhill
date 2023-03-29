<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'title' => $this->faker->title,
            'content' => $this->faker->text,
            'metadata' => $this->generateMetadata(),
        ];
    }

    private function generateMetadata(): array
    {
        $validFrom = $this->faker->dateTimeBetween('-1 year', 'now');
        $validTo = $this->faker->dateTimeBetween($validFrom, '+1 year');

        return [
            'valid_from' => $validFrom->format('Y-m-d'),
            'valid_to' => $validTo->format('Y-m-d'),
            'image' => $this->faker->uuid,
        ];
    }
}
