<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            My Dashboard
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">My Decisions</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalMyDecisions }}
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">My Average Score</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $avgMyScore ? number_format($avgMyScore, 1) : '—' }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Based on graded decisions
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Pending Tasks</div>
                    <div class="mt-2 text-2xl font-bold text-red-600">
                        {{ $pendingCount }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Scenarios without your decision
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">My Files</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $filesCount }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Uploaded attachments
                    </div>
                </div>
            </div>

            {{-- Chart + My recent decisions --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- My score history chart --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        My Score History
                    </h3>

                    @if(empty($scoreData))
                        <p class="text-sm text-gray-500">
                            You do not have any graded decisions yet.
                        </p>
                    @else
                        <canvas id="myScoresChart" height="140"></canvas>
                    @endif
                </div>

                {{-- My latest decisions --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        My Recent Decisions
                    </h3>

                    @if($myRecentDecisions->isEmpty())
                        <p class="text-sm text-gray-500">You have not submitted any decisions yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($myRecentDecisions as $decision)
                                <div class="border-b border-gray-100 pb-2 last:border-b-0">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $decision->scenario->title ?? 'Unknown scenario' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Submitted: {{ $decision->created_at->diffForHumans() }}
                                    </div>
                                    <div class="text-xs text-gray-700 mt-1">
                                        Score:
                                        <span class="font-bold">
                                            {{ optional($decision->result)->score ?? 'Not graded' }}
                                        </span>
                                    </div>
                                    <div class="text-xs mt-1">
                                        <a href="{{ route('scenarios.show', $decision->scenario) }}"
                                           class="text-indigo-600 underline">
                                            View scenario
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- My scenarios + Pending scenarios + My files --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- My scenarios --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        My Scenarios
                    </h3>

                    @if($myScenarios->isEmpty())
                        <p class="text-sm text-gray-500">
                            You have not submitted any decisions yet. Start from the scenario list.
                        </p>
                    @else
                        <div class="space-y-3">
                            @foreach($myScenarios as $scenario)
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2 last:border-b-0">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $scenario->title }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Budget: £{{ $scenario->budget }} · Duration: {{ $scenario->duration }} days
                                        </div>
                                    </div>
                                    <div class="text-xs">
                                        <a href="{{ route('scenarios.show', $scenario) }}"
                                           class="text-indigo-600 underline">
                                            Open
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Pending scenarios --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Pending Scenarios
                    </h3>

                    @if($pendingScenarios->isEmpty())
                        <p class="text-sm text-gray-500">
                            Great! You have submitted decisions for all current scenarios.
                        </p>
                    @else
                        <div class="space-y-3">
                            @foreach($pendingScenarios as $scenario)
                                <div class="border-b border-gray-100 pb-2 last:border-b-0">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $scenario->title }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Budget: £{{ $scenario->budget }} · Duration: {{ $scenario->duration }} days
                                    </div>
                                    <div class="mt-1 text-xs">
                                        <a href="{{ route('decisions.create', $scenario) }}"
                                           class="text-green-600 underline">
                                            Submit decision
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- My files --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    My Files
                </h3>

                @if($myFiles->isEmpty())
                    <p class="text-sm text-gray-500">
                        You have not uploaded any attachments yet.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($myFiles as $decision)
                            <div class="flex justify-between items-start border-b border-gray-100 pb-2 last:border-b-0">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $decision->scenario->title ?? 'Unknown scenario' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Uploaded: {{ $decision->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="text-xs text-right">
                                    <a href="{{ asset('storage/' . $decision->file_path) }}"
                                       target="_blank"
                                       class="text-indigo-600 underline">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('myScoresChart')?.getContext('2d');

            @if(!empty($scoreData))
            if (ctx) {
                const labels = @json($scoreLabels);
                const data   = @json($scoreData);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Score',
                            data: data,
                            borderWidth: 2,
                            tension: 0.3,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }
            @endif
        });
    </script>
</x-app-layout>
