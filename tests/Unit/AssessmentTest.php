<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Test as TestModel;
use App\Models\Assessment;

class AssessmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_assessment_belongs_to_a_judge()
    {
        $judge = User::factory()->judge()->create();
        $assessment = Assessment::factory()->for($judge, 'judge')->create();

        $this->assertInstanceOf(User::class, $assessment->judge);
        $this->assertEquals('judge', $assessment->judge->role);
    }

    public function test_an_assessment_belongs_to_a_test()
    {
        $test = TestModel::factory()->create();
        $assessment = Assessment::factory()->for($test)->create();

        $this->assertInstanceOf(TestModel::class, $assessment->test);
    }
}
