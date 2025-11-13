<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Scenario;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Decision>
 */
class DecisionFactory extends Factory
{
    public function definition()
    {
        return [
            'scenario_id' => Scenario::factory(),
            'user_id' => User::factory(),
            'strategy' => $this->faker->paragraph(1),
            'time_alloc' => $this->faker->numberBetween(10,90),
            'cost_alloc' => $this->faker->numberBetween(2000,30000),
            'risk_level' => $this->faker->randomElement(['low','medium','high']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
