<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Test as TestModel;
use App\Models\Assessment;

class TestTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_test_belongs_to_a_user()
    {
        $contender = User::factory()->contender()->create();
        $test = TestModel::factory()->for($contender, 'user')->create();

        $this->assertInstanceOf(User::class, $test->user);
        $this->assertEquals('contender', $test->user->role);
    }

    public function test_a_test_can_have_many_judges()
    {
        $test = TestModel::factory()->create();
        $judges = User::factory()->count(3)->judge()->create();

        $test->judges()->attach($judges->pluck('id')->toArray());

        $this->assertCount(3, $test->judges);
        $this->assertInstanceOf(User::class, $test->judges->first());
    }

    public function test_a_test_can_have_many_assessments()
    {
        $test = TestModel::factory()->create();
        $assessments = Assessment::factory()->count(2)->for($test)->create();

        $this->assertCount(2, $test->assessments);
        $this->assertInstanceOf(Assessment::class, $test->assessments->first());
    }
}
