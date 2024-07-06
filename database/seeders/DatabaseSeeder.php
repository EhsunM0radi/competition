<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Test;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create judges
        $judges = User::factory()->count(5)->judge()->create();

        // Create contenders and their tests
        $contenders = User::factory()->count(10)->contender()->create();

        foreach ($contenders as $contender) {
            $tests = Test::factory()->count(2)->for($contender, 'user')->create();

            foreach ($tests as $test) {
                // Assign random judges to the test
                $assignedJudges = $judges->random(rand(1, 3))->pluck('id')->toArray();
                $test->judges()->attach($assignedJudges);

                // Randomly create assessments for some of the assigned judges
                foreach ($test->judges as $judge) {
                    if (rand(0, 1)) { // Randomly decide to create or not create an assessment
                        $assessment = Assessment::factory()->for($test)->for($judge, 'judge')->create();

                        // Determine the type of assessment
                        if (count($assignedJudges) > 1) {
                            $assessment->type = 'group';
                        } else {
                            $assessment->type = 'individual';
                        }

                        $assessment->save();
                    }
                }
            }
        }
    }
}
