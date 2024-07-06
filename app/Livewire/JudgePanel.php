<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class JudgePanel extends Component
{
    public $testsToAssess;
    public $assessedTests;
    public $expandedTestId;
    public $score;
    public $editing = false;

    public function mount()
    {
        $this->loadTests();
    }

    public function loadTests()
    {
        $judgeId = Auth::id();
        $this->testsToAssess = Test::whereDoesntHave('assessments', function ($query) use ($judgeId) {
            $query->where('judge_id', $judgeId);
        })->get();

        $this->assessedTests = Test::whereHas('assessments', function ($query) use ($judgeId) {
            $query->where('judge_id', $judgeId);
        })->with('assessments')->get();
    }

    public function expandTest($testId)
    {
        $this->expandedTestId = $testId;
        $this->score = null;
        $this->editing = false;
    }

    public function saveScore($testId)
    {
        Assessment::create([
            'judge_id' => Auth::id(),
            'test_id' => $testId,
            'score' => $this->score,
            'type' => 'individual', // or 'group' depending on your logic
        ]);

        $this->loadTests();
    }

    public function editScore($testId)
    {
        $this->editing = true;
        $this->score = Assessment::where('judge_id', Auth::id())
            ->where('test_id', $testId)
            ->first()
            ->score;
    }

    public function updateScore($testId)
    {
        $assessment = Assessment::where('judge_id', Auth::id())
            ->where('test_id', $testId)
            ->first();

        $assessment->update([
            'score' => $this->score,
        ]);

        $this->loadTests();
    }

    public function render()
    {
        return view('livewire.judge-panel', [
            'testsToAssess' => $this->testsToAssess,
            'assessedTests' => $this->assessedTests,
        ]);
    }
}
