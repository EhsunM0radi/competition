<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Assessment::class;


    public function definition(): array
    {
        return [
            'judge_id' => User::factory()->judge(),
            'test_id' => Test::factory(),
            'score' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->randomElement(['group', 'individual']),
        ];
    }
}
