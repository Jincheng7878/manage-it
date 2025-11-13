<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scenario>
 */
class ScenarioFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(2),
            'budget' => $this->faker->numberBetween(5000, 50000),
            'duration' => $this->faker->numberBetween(14, 120),
            'difficulty' => $this->faker->randomElement(['easy','medium','hard']),
            'initial_metrics' => ['team_skill' => $this->faker->numberBetween(1,10)],
            'created_by' => User::factory(), // 会自动创建 user 或使用已有用户
        ];
    }
}
