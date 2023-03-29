<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
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
            'slug' => $this->faker->slug,
            'content' => $this->faker->text,
            'metadata' => $this->generateMetadata(),
        ];
    }

    private function generateMetadata(): array
    {
        return [
            'author' => $this->faker->name,
            'image' => $this->faker->uuid,
        ];
    }
}
