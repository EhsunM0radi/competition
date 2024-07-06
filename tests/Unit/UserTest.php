<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Test as TestModel;
use App\Models\Assessment;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_judge_can_have_many_assessments()
    {
        $judge = User::factory()->judge()->create();
        $assessments = Assessment::factory()->count(3)->for($judge, 'judge')->create();

        $this->assertCount(3, $judge->assessments);
        $this->assertInstanceOf(Assessment::class, $judge->assessments->first());
    }

    public function test_a_contender_can_have_many_tests()
    {
        $contender = User::factory()->contender()->create();
        $tests = TestModel::factory()->count(2)->for($contender, 'user')->create();

        $this->assertCount(2, $contender->tests);
        $this->assertInstanceOf(TestModel::class, $contender->tests->first());
    }

    public function test_a_judge_can_have_many_tests_to_assess()
    {
        $judge = User::factory()->judge()->create();
        $tests = TestModel::factory()->count(2)->create();

        $judge->testsToAssess()->attach($tests->pluck('id')->toArray());

        $this->assertCount(2, $judge->testsToAssess);
        $this->assertInstanceOf(TestModel::class, $judge->testsToAssess->first());
    }
}

