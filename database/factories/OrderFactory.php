<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_id' => Payment::factory(),
            'order_status_id' => OrderStatus::inRandomOrder()->first()->id,
            'uuid' => $this->faker->uuid,
            'products' => $this->generateProducts(),
            'address' => $this->generateAddress(),
            'delivery_fee' => $this->faker->randomElement([null, rand(1, 100)]),
            'amount' => rand(1, 100),
            'shipped_at' => $this->faker->randomElement([null, now()]),
        ];
    }

    private function generateProducts()
    {
        $numberOfProducts = rand(1, 5);
        $products = [];

        for ($i = 0; $i < $numberOfProducts; $i++) {
            $products[] = [
                'product' => Str::uuid(),
                'quantity' => rand(1, 10),
            ];
        }

        return $products;
    }

    private function generateAddress()
    {
        return [
            'billing' => $this->faker->address,
            'shipping' => $this->faker->address,
        ];
    }
}
