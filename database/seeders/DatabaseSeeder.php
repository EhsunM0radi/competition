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
        // User::factory(10)->create();

        // Create judges
        $judges = User::factory()->count(5)->judge()->create();

        // Create contenders and their tests
        $contenders = User::factory()->count(10)->contender()->create();

        foreach ($contenders as $contender) {
            $tests = Test::factory()->count(2)->for($contender, 'user')->create();

            foreach ($tests as $test) {
                // Assign random judges to the test
                $test->judges()->attach(
                    $judges->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Create assessments for the test by judges
                foreach ($test->judges as $judge) {
                    Assessment::factory()->for($test)->for($judge, 'judge')->create();
                }
            }
        }
    }
}
