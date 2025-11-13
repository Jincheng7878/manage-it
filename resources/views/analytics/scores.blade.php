<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            Score Analytics – Scenario Overview
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4 border">
                    <h3 class="text-sm font-semibold text-gray-500">Overall Average Score</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $overallAverage }}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border">
                    <h3 class="text-sm font-semibold text-gray-500">Scenarios with Grades</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ $totalScenarios }}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border">
                    <h3 class="text-sm font-semibold text-gray-500">Total Graded Decisions</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ $totalDecisions }}</p>
                </div>
            </div>

            {{-- Chart card --}}
            <div class="bg-white rounded-xl shadow p-6 border">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Average Score per Scenario
                        </h3>
                        <p class="text-sm text-gray-500">
                            Each bar shows the average score of all graded decisions for a scenario.
                        </p>
                    </div>
                </div>

                @if($scenarioLabels->isEmpty())
                    <p class="text-sm text-gray-500">No graded decisions yet. Once teachers grade decisions, analytics will appear here.</p>
                @else
                    <div class="relative">
                        <canvas id="scenarioScoreChart" class="w-full h-80"></canvas>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(!$scenarioLabels->isEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('scenarioScoreChart').getContext('2d');

                const labels = @json($scenarioLabels);
                const dataScores = @json($scenarioAvgScores);
                const dataCounts = @json($scenarioGradeCount);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Score',
                            data: dataScores,
                            borderWidth: 1,
                            // 不指定颜色的话 Chart.js 会自动配色，你如果想固定颜色也可以后面再改
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Score'
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 40,
                                    minRotation: 0
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    afterBody: function(context) {
                                        const index = context[0].dataIndex;
                                        const count = dataCounts[index] ?? 0;
                                        return 'Graded decisions: ' + count;
                                    }
                                }
                            },
                            legend: {
                                display: true
                            }
                        }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
