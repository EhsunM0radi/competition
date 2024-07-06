<div>
    <h2>Tests to Assess</h2>
    <div>
        @foreach($testsToAssess as $test)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $test->user->name }}</h5>
                    <p class="card-text">{{ Str::limit($test->content, 100) }}</p>
                    <button wire:click="expandTest({{ $test->id }})">View</button>
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
                </div>
            </div>
        @endforeach
    </div>
</div>
