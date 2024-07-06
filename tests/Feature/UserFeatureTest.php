<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Test as TestModel;
use App\Models\Assessment;

class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_judge_and_contender()
    {
        // Create a judge
        $judge = User::factory()->judge()->create();
        $this->assertDatabaseHas('users', [
            'id' => $judge->id,
            'role' => 'judge'
        ]);

        // Create a contender
        $contender = User::factory()->contender()->create();
        $this->assertDatabaseHas('users', [
            'id' => $contender->id,
            'role' => 'contender'
        ]);
    }

    public function test_it_can_create_tests_and_assign_judges()
    {
        // Create a contender and a test
        $contender = User::factory()->contender()->create();
        $test = TestModel::factory()->for($contender, 'user')->create();

        // Create judges
        $judges = User::factory()->count(3)->judge()->create();

        // Attach judges to the test
        $test->judges()->attach($judges->pluck('id')->toArray());

        $this->assertCount(3, $test->judges);
        foreach ($judges as $judge) {
            $this->assertDatabaseHas('test_user', [
                'test_id' => $test->id,
                'user_id' => $judge->id
            ]);
        }
    }

    public function test_it_can_create_assessments_for_a_test_by_judges()
    {
        // Create a test
        $contender = User::factory()->contender()->create();
        $test = TestModel::factory()->for($contender, 'user')->create();

        // Create a judge and attach to the test
        $judge = User::factory()->judge()->create();
        $test->judges()->attach($judge->id);

        // Create an assessment
        $assessment = Assessment::factory()->for($test)->for($judge, 'judge')->create();

        $this->assertDatabaseHas('assessments', [
            'id' => $assessment->id,
            'test_id' => $test->id,
            'judge_id' => $judge->id
        ]);
    }

    public function test_it_can_list_all_tests_for_a_judge()
    {
        // Create a judge
        $judge = User::factory()->judge()->create();

        // Create tests and attach to judge
        $tests = TestModel::factory()->count(2)->create();
        $judge->testsToAssess()->attach($tests->pluck('id')->toArray());

        // Fetch tests from the database
        $this->assertCount(2, $judge->testsToAssess);
        foreach ($tests as $test) {
            $this->assertTrue($judge->testsToAssess->contains($test));
        }
    }
}
