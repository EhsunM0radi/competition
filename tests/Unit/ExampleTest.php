<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\Test;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_creates_judges_and_contenders()
    {
        // Generate users
        $judge = User::factory()->judge()->create();
        $contender = User::factory()->contender()->create();

        // Assert roles
        $this->assertEquals('judge', $judge->role);
        $this->assertEquals('contender', $contender->role);
    }

    /** @test */
    public function it_creates_tests_and_assessments()
    {
        // Generate a contender and a test
        $contender = User::factory()->contender()->create();
        $test = Test::factory()->for($contender, 'user')->create();

        // Generate a judge and an assessment
        $judge = User::factory()->judge()->create();
        $assessment = Assessment::factory()->for($test)->for($judge, 'judge')->create();

        // Assert relationships
        $this->assertTrue($contender->tests->contains($test));
        $this->assertTrue($judge->assessments->contains($assessment));
        $this->assertEquals($assessment->judge->id, $judge->id);
        $this->assertEquals($assessment->test->id, $test->id);
    }
}
