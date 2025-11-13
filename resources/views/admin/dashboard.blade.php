<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            Admin Analytics Dashboard
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary cards (current dashboard) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Total Scenarios</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalScenarios }}
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Total Users</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalUsers }}
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Total Decisions</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalDecisions }}
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Average Score</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $averageScore ? number_format($averageScore, 1) : '—' }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Based on all graded decisions
                    </div>
                </div>
            </div>

            {{-- Line chart + pending card --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Decisions trend chart (line) --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Decisions Submitted (Last 7 Days)
                    </h3>
                    <canvas id="decisionsChart" height="120"></canvas>
                </div>

                {{-- Pending decisions card --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        Pending Decisions
                    </h3>
                    <p class="text-4xl font-bold text-red-600">
                        {{ $pendingDecisionsCount }}
                    </p>
                    <p class="text-sm text-gray-500 mt-2">
                        Decisions that have not been graded yet.
                    </p>
                    <p class="text-xs text-gray-400 mt-4">
                        You can grade them from each scenario detail page or the grading list.
                    </p>
                </div>

            </div>

            {{-- 🔻 这里是你原来的 Score Analytics – Scenario Overview 模块（融合进来了） --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Score Analytics – Scenario Overview
                </h2>

                {{-- Top three cards (from your old scores.blade) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow p-4 border">
                        <h3 class="text-sm font-semibold text-gray-500">Overall Average Score</h3>
                        <p class="mt-2 text-3xl font-bold text-indigo-700">
                            {{ $overallAverage ?? '—' }}
                        </p>
                    </div>

                    <div class="bg-white rounded-xl shadow p-4 border">
                        <h3 class="text-sm font-semibold text-gray-500">Scenarios with Grades</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-800">
                            {{ $scenariosWithGrades }}
                        </p>
                    </div>

                    <div class="bg-white rounded-xl shadow p-4 border">
                        <h3 class="text-sm font-semibold text-gray-500">Total Graded Decisions</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-800">
                            {{ $totalGradedDecisions }}
                        </p>
                    </div>
                </div>

                {{-- Bar chart card --}}
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
                        <p class="text-sm text-gray-500">
                            No graded decisions yet. Once teachers grade decisions, analytics will appear here.
                        </p>
                    @else
                        <div class="relative">
                            <canvas id="scenarioScoreChart" class="w-full h-80"></canvas>
                        </div>
                    @endif
                </div>
            </div>
            {{-- 🔺 到这里，原来的分数柱状图已经被完整融合 --}}

            {{-- Recent decisions + ungraded decisions --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Recent decisions --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Recent Decisions
                    </h3>

                    @if($recentDecisions->isEmpty())
                        <p class="text-sm text-gray-500">No decisions yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($recentDecisions as $decision)
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2 last:border-b-0">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $decision->user->name ?? 'Unknown user' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Scenario: {{ $decision->scenario->title ?? 'Unknown scenario' }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $decision->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="text-right text-sm">
                                        <div class="text-gray-700">
                                            <span class="text-xs text-gray-500">Score: </span>
                                            <span class="font-bold">
                                                {{ optional($decision->result)->score ?? '—' }}
                                            </span>
                                        </div>
                                        @if($decision->result)
                                            <div class="text-xs text-gray-500 mt-1">
                                                Graded
                                            </div>
                                        @else
                                            <div class="text-xs text-red-500 mt-1">
                                                Not graded
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Ungraded decisions --}}
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Ungraded Decisions (Latest)
                    </h3>

                    @if($ungradedDecisions->isEmpty())
                        <p class="text-sm text-gray-500">All decisions are graded. 🎉</p>
                    @else
                        <div class="space-y-3">
                            @foreach($ungradedDecisions as $decision)
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2 last:border-b-0">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $decision->user->name ?? 'Unknown user' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Scenario: {{ $decision->scenario->title ?? 'Unknown scenario' }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $decision->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="text-right text-xs">
                                        <a href="{{ route('results.gradeList', $decision->scenario) }}"
                                           class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 transition">
                                            Grade
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --------- Line chart: decisions per day ---------
            const ctxLine = document.getElementById('decisionsChart')?.getContext('2d');
            if (ctxLine) {
                const labels = @json($chartLabels);
                const data   = @json($chartData);

                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Decisions per day',
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
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }

            // --------- Bar chart: average score per scenario ---------
            @if(!$scenarioLabels->isEmpty())
            const ctxBar = document.getElementById('scenarioScoreChart')?.getContext('2d');
            if (ctxBar) {
                const labels2       = @json($scenarioLabels);
                const dataScores    = @json($scenarioAvgScores);
                const dataCounts    = @json($scenarioGradeCount);

                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: labels2,
                        datasets: [{
                            label: 'Average Score',
                            data: dataScores,
                            borderWidth: 1,
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
            }
            @endif
        });
    </script>
</x-app-layout>
