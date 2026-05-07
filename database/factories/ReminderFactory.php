<?php

namespace Database\Factories;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reminder>
 */
class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            'title'     => fake()->sentence(),
            'note'      => fake()->paragraph(),
            'remind_at' => now()->addHour(),
            'isSent'   => false,
            'sent_at'   => null,
        ];
    }
}
