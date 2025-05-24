<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Courier>
 */
class CourierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();
        $email = Str::lower(str_replace(' ', '.', $name)) . '@' . fake()->freeEmailDomain();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'phone' => $this->faker->numerify('08##########'),
            'email' => $email,
            'address' => $this->faker->address(),
        ];

    }
}
