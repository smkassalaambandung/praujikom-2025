<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'pesta rakyat ' . fake()->country(),
            'description' => fake()->sentence(),
            'event_date' => fake()->date(),
            'location' => fake()->country(),
            'user_id' => fake()->numberBetween(1, 100),
        ];
    }
}
