<div>
    <h2>Tests to Assess</h2>
    <div>
        @foreach($testsToAssess as $test)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $test->user->name }}</h5>
                    <p class="card-text">{{ Str::limit($test->content, 100) }}</p>
                    <button wire:click="expandTest({{ $test->id }})">View</button>
                    @if($expandedTestId == $test->id)
                        <div class="expanded-content">
                            <p>{{ $test->content }}</p>
                            <input type="number" wire:model="score">
                            <button wire:click="saveScore({{ $test->id }})">Save</button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <h2>Assessed Tests</h2>
    <div>
        @foreach($assessedTests as $test)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $test->user->name }}</h5>
                    <p class="card-text">{{ Str::limit($test->content, 100) }}</p>
                    <p>Score: {{ $test->assessment->score }}</p>
                    <button wire:click="expandTest({{ $test->id }})">View</button>
                    @if($expandedTestId == $test->id)
                        <div class="expanded-content">
                            <p>{{ $test->content }}</p>
                            <p>Score: {{ $test->assessment->score }}</p>
                            <button wire:click="editScore({{ $test->id }})">Edit</button>
                            @if($editing)
                                <input type="number" wire:model="score">
                                <button wire:click="updateScore({{ $test->id }})">Update</button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
