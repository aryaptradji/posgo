<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();
        $email = Str::lower(str_replace(' ', '.', $name)) . '@' . fake()->freeEmailDomain();
        $plain = 'kasir123';

        return [
            'created' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'name' => $name,
            'slug' => Str::slug($name),
            'email' => $email,
            'password' => Hash::make($plain),
            'phone_number' => $this->faker->phoneNumber(),
            'address_id' => Address::factory(),
            'photo' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function cashier(): static
    {
        return $this->state(function () {
            $plain = 'kasir123';

            return [
                'role' => 'cashier',
                'password' => Hash::make($plain),
                'plaintext_password' => $plain,
            ];
        });
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
