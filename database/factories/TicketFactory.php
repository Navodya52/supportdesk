<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ticket;
/**
 * @extends Factory<Ticket>
 */
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->employee(),
            'assigned_to' => null,
            'category_id' => Category::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement([
                Ticket::PRIORITY_LOW,
                Ticket::PRIORITY_MEDIUM,
                Ticket::PRIORITY_HIGH,
                Ticket::PRIORITY_CRITICAL,
            ]),
            'status' => Ticket::STATUS_OPEN,
            'resolution' => null,
            'resolved_at' => null,
            'closed_at' => null,
        ];
    }

    public function assigned(User $agent): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to' => $agent->id,
            'status' => Ticket::STATUS_IN_PROGRESS,
        ]);
    }

    public function resolved(string $resolution = 'Resolved by technician.'): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Ticket::STATUS_RESOLVED,
            'resolution' => $resolution,
            'resolved_at' => now(),
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Ticket::STATUS_CLOSED,
            'closed_at' => now(),
        ]);
    }
}
