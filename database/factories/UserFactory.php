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
        $localPart = Str::lower(str_replace(' ', '.', $name));
        $localPart = rtrim($localPart, '.'); // Hapus titik di akhir
        $localPart = preg_replace('/\.{2,}/', '.', $localPart); // Replace double dot jadi single dot
        $email = $localPart . '@' . fake()->freeEmailDomain();
        $plain = '123';

        return [
            'created' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'name' => $name,
            'slug' => Str::slug($name),
            'role' => 'customer',
            'email' => $email,
            'password' => Hash::make($plain),
            'phone_number' => $this->faker->numerify('08##########'),
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
                'address_id' => null
            ];
        });
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'address_id' => null
        ]);
    }
}
