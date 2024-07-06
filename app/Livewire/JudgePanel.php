<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class JudgePanel extends Component
{
    public $testsToAssess = [];
    public $assessedTests = [];
    public $expandedTestId = null;
    public $score;
    public $editing = false;

    protected $rules = [
        'score' => 'required|integer|min:0|max:100',
    ];

    public function mount()
    {
        $this->loadTests();
    }

    public function loadTests()
    {
        $judgeId = Auth::id();

        // Fetch tests assigned to the logged-in judge that haven't been assessed yet
        $this->testsToAssess = Test::whereHas('judges', function ($query) use ($judgeId) {
            $query->where('judge_id', $judgeId);
        })->whereDoesntHave('assessments', function ($query) use ($judgeId) {
            $query->where('judge_id', $judgeId);
        })->get();

        // Fetch tests assigned to the logged-in judge that have been assessed
        $this->assessedTests = Test::whereHas('judges', function ($query) use ($judgeId) {
            $query->where('judge_id', $judgeId);
        })->whereHas('assessments', function ($query) use ($judgeId) {
            $query->where('judge_id', $judgeId);
        })->get();
    }

    public function expandTest($testId)
    {
        $this->expandedTestId = $testId;
        $this->editing = false;
    }

    public function saveScore($testId)
    {
        $this->validate(); // Validates the 'score' property

        $judgeId = Auth::id();
        $test = Test::find($testId);

        // Check if the assessment already exists
        $assessment = Assessment::where('test_id', $testId)->where('judge_id', $judgeId)->first();

        if ($assessment) {
            // If it exists, update the score
            $assessment->score = $this->score;
        } else {
            // If it doesn't exist, create a new assessment
            $assessment = new Assessment();
            $assessment->judge_id = $judgeId;
            $assessment->test_id = $testId;
            $assessment->score = $this->score;

            // Determine assessment type
            if ($test->judges()->count() > 1) {
                $assessment->type = 'group';
            } else {
                $assessment->type = 'individual';
            }
        }

        $assessment->save();

        $this->loadTests();
        $this->expandedTestId = null;
        $this->score = null;
    }

    public function editScore($testId)
    {
        $this->expandedTestId = $testId;
        $this->editing = true;
        $assessment = Assessment::where('test_id', $testId)->where('judge_id', Auth::id())->first();
        $this->score = $assessment->score;
    }

    public function updateScore($testId)
    {
        $this->validate();
        $assessment = Assessment::where('test_id', $testId)->where('judge_id', Auth::id())->first();
        $assessment->score = $this->score;
        $assessment->save();

        $this->loadTests();
        $this->expandedTestId = null;
        $this->editing = false;
        $this->score = null;
    }

    public function render()
    {

        return view('livewire.judge-panel', [
            'testsToAssess' => $this->testsToAssess,
            'assessedTests' => $this->assessedTests,
        ]);
    }
}
