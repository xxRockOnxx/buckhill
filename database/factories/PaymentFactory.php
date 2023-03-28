<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement([
            'credit_card',
            'cash_on_delivery',
            'bank_transfer',
        ]);

        return [
            'uuid' => $this->faker->uuid,
            'type' => $type,
            'details' => $this->generateDetails($type),
        ];
    }

    private function generateDetails(string $type)
    {
        switch ($type) {
            case 'credit_card':
                return [
                    'holder_name' => $this->faker->name,
                    'number' => $this->faker->creditCardNumber,
                    'ccv' => $this->faker->creditCardExpirationDateString,
                    'expire_date' => $this->faker->creditCardExpirationDate,
                ];

            case 'cash_on_delivery':
                return [
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'address' => $this->faker->address,
                ];

            case 'bank_transfer':
                return [
                    'swift' => $this->faker->swiftBicNumber,
                    'iban' => $this->faker->iban('US'),
                    'name' => $this->faker->name,
                ];

            default:
                throw new \Exception('Invalid payment type');
        }
    }
}
