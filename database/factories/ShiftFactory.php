<?php

namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'worker_id' => Worker::factory(),
            'date' => now()->format('Y-m-d'),
            'start_at' => now()->toTimeString(),
            'end_at' => now()->addHours(8)->toTimeString(),
            'slot' => '0-8'
        ];
    }
}
