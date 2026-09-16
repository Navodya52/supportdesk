<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Ticket;
/**
 * @extends Factory<Comment>
 */
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => User::factory(),
            'comment' => fake()->paragraph(),
        ];
    }
}
