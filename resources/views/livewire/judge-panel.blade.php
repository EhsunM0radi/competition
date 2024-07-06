<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Tests to Assess</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($testsToAssess as $test)
            <div class="bg-white shadow-lg rounded-lg p-4">
                <div class="card-body">
                    <h5 class="text-xl font-semibold">{{ $test->user->name }}</h5>
                    <p class="text-gray-600">{{ Str::limit($test->content, 100) }}</p>
                    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded" wire:click="expandTest({{ $test->id }})">View</button>
                    @if($expandedTestId == $test->id)
                        <div class="mt-4 bg-gray-100 p-4 rounded">
                            <p>{{ $test->content }}</p>
                            <input type="number" class="mt-2 border-gray-300 rounded" wire:model="score" placeholder="Enter Score">
                            @error('score') <span class="text-red-500">{{ $message }}</span> @enderror
                            <button class="mt-2 bg-green-500 text-white px-4 py-2 rounded" wire:click="saveScore({{ $test->id }})">Save</button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <h2 class="text-2xl font-bold mt-8 mb-4">Assessed Tests</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($assessedTests as $testIndex => $test)
            @php
                $assessments = $test->assessments->where('judge_id',auth()->user()->id)->values();
                $assessmentsSum = 0;
                $assessmentsCount = 0;
                foreach ($assessments as $assessment) {
                    $assessmentsSum += $assessment->score;
                    $assessmentsCount += 1;
                }
                $assessmentsAvg = $assessmentsCount > 0 ? $assessmentsSum / $assessmentsCount : 0;
            @endphp
            <div class="bg-white shadow-lg rounded-lg p-4">
                <div class="card-body">
                    <h5 class="text-xl font-semibold">{{ $test->user->name }}</h5>
                    <p class="text-gray-600">{{ Str::limit($test->content, 100) }}</p>
                    <p class="mt-2">Score: <span class="font-bold">{{ $assessmentsAvg }}</span></p>
                    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded" wire:click="expandTest({{ $test->id }})">View</button>
                    @if($expandedTestId == $test->id)
                        <div class="mt-4 bg-gray-100 p-4 rounded">
                            <p>{{ $test->content }}</p>
                            <p>Score: <span class="font-bold">{{ $assessmentsAvg }}</span></p>
                            <button class="mt-2 bg-yellow-500 text-white px-4 py-2 rounded" wire:click="editScore({{ $test->id }})">Edit</button>
                            @if($editing)
                                <input type="number" class="mt-2 border-gray-300 rounded" wire:model="score" placeholder="Enter New Score">
                                @error('score') <span class="text-red-500">{{ $message }}</span> @enderror
                                <button class="mt-2 bg-green-500 text-white px-4 py-2 rounded" wire:click="updateScore({{ $test->id }})">Update</button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
