@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Grade Decisions – {{ $scenario->title }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Scenario Overview --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 border border-gray-200">
                <h3 class="text-lg font-semibold mb-2">Scenario Overview</h3>
                <p class="text-gray-700 mb-2">
                    {{ $scenario->description }}
                </p>
                <div class="text-xs text-gray-500 space-y-1">
                    <p>
                        <strong>Budget:</strong> £{{ $scenario->budget }}
                        · <strong>Duration:</strong> {{ $scenario->duration }} days
                        · <strong>Difficulty:</strong> {{ ucfirst($scenario->difficulty) }}
                    </p>
                    <p>
                        <strong>Status:</strong>
                        @if($scenario->isOpenForSubmission())
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                                Open
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">
                                Closed
                            </span>
                        @endif
                    </p>
                    <p>
                        <strong>Deadline:</strong>
                        @if($scenario->deadline)
                            {{ $scenario->deadline->format('Y-m-d H:i') }}
                            @if(now()->greaterThan($scenario->deadline))
                                <span class="text-[10px] text-red-600">(passed)</span>
                            @endif
                        @else
                            <span class="text-[11px] text-gray-400">No deadline set</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Decisions List --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                Student Decisions
            </h3>

            @forelse($scenario->decisions as $decision)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4 border border-gray-200">

                    {{-- Header: student + time --}}
                    <div class="flex justify-between items-baseline mb-2">
                        <div class="text-sm font-semibold text-gray-900">
                            {{ $decision->user->name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Submitted: {{ $decision->created_at->diffForHumans() }}
                        </div>
                    </div>

                    {{-- Status + current score --}}
                    <div class="flex items-center gap-3 mb-3">
                        @if($decision->result)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                                Graded
                            </span>
                            <span class="text-xs text-gray-700">
                                Score: <span class="font-bold">{{ $decision->result->score }}</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                Not graded
                            </span>
                        @endif
                    </div>

                    {{-- Strategy summary --}}
                    <div class="mb-3">
                        <div class="text-xs font-semibold text-gray-600 mb-1">
                            Strategy (summary)
                        </div>
                        <div class="text-sm text-gray-700">
                            {{ Str::limit($decision->strategy, 220) }}
                        </div>
                    </div>

                    {{-- Collapsible details: full strategy & structured analysis --}}
                    <details class="mb-3">
                        <summary class="text-xs text-indigo-600 cursor-pointer hover:underline">
                            Show full decision details
                        </summary>

                        <div class="mt-3 space-y-3 text-xs text-gray-700">

                            {{-- Full strategy --}}
                            <div>
                                <div class="font-semibold text-gray-700 mb-1">
                                    Full Strategy
                                </div>
                                <div class="whitespace-pre-line">
                                    {{ $decision->strategy }}
                                </div>
                            </div>

                            {{-- SWOT --}}
                            @if($decision->swot_strengths || $decision->swot_weaknesses || $decision->swot_opportunities || $decision->swot_threats)
                                <div>
                                    <div class="font-semibold text-gray-700 mb-1">
                                        SWOT Analysis
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @if($decision->swot_strengths)
                                            <div>
                                                <div class="font-semibold text-gray-600">Strengths</div>
                                                <div class="whitespace-pre-line mt-1">
                                                    {{ $decision->swot_strengths }}
                                                </div>
                                            </div>
                                        @endif

                                        @if($decision->swot_weaknesses)
                                            <div>
                                                <div class="font-semibold text-gray-600">Weaknesses</div>
                                                <div class="whitespace-pre-line mt-1">
                                                    {{ $decision->swot_weaknesses }}
                                                </div>
                                            </div>
                                        @endif

                                        @if($decision->swot_opportunities)
                                            <div>
                                                <div class="font-semibold text-gray-600">Opportunities</div>
                                                <div class="whitespace-pre-line mt-1">
                                                    {{ $decision->swot_opportunities }}
                                                </div>
                                            </div>
                                        @endif

                                        @if($decision->swot_threats)
                                            <div>
                                                <div class="font-semibold text-gray-600">Threats</div>
                                                <div class="whitespace-pre-line mt-1">
                                                    {{ $decision->swot_threats }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- WBS --}}
                            @if($decision->wbs)
                                <div>
                                    <div class="font-semibold text-gray-700 mb-1">
                                        Work Breakdown Structure (WBS)
                                    </div>
                                    <div class="whitespace-pre-line">
                                        {{ $decision->wbs }}
                                    </div>
                                </div>
                            @endif

                            {{-- Risk matrix --}}
                            @if($decision->risk_matrix)
                                <div>
                                    <div class="font-semibold text-gray-700 mb-1">
                                        Risk Matrix
                                    </div>
                                    <div class="whitespace-pre-line">
                                        {{ $decision->risk_matrix }}
                                    </div>
                                </div>
                            @endif

                            {{-- Cost breakdown --}}
                            @if($decision->cost_breakdown)
                                <div>
                                    <div class="font-semibold text-gray-700 mb-1">
                                        Cost Breakdown
                                    </div>
                                    <div class="whitespace-pre-line">
                                        {{ $decision->cost_breakdown }}
                                    </div>
                                </div>
                            @endif

                        </div>
                    </details>

                    {{-- Attachment (if any) --}}
                    @if($decision->file_path)
                        <div class="mb-3 text-xs">
                            <span class="font-semibold text-gray-700">Attachment:</span>
                            <a href="{{ asset('storage/' . $decision->file_path) }}"
                               class="text-blue-600 underline"
                               target="_blank">
                                Download student file
                            </a>
                        </div>
                    @endif

                    {{-- Grading form (separate block at bottom, full width) --}}
                    <form action="{{ route('results.grade', $decision) }}"
                          method="POST"
                          class="mt-4 pt-4 border-t border-gray-200">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">

                            {{-- Score --}}
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    Score
                                </label>
                                <input type="number"
                                       name="score"
                                       min="0"
                                       max="100"
                                       value="{{ $decision->result->score ?? '' }}"
                                       class="w-full border rounded p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                       required>
                            </div>

                            {{-- Feedback --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-800 mb-1">
                                    Feedback
                                </label>
                                <textarea name="feedback"
                                          rows="3"
                                          class="w-full border rounded p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Write teacher feedback here...">{{ $decision->result->feedback ?? '' }}</textarea>
                            </div>

                            {{-- Save button --}}
                            <div class="md:col-span-1 flex md:items-end">
                                <button
                                    class="w-full border border-gray-800 text-gray-900 py-2 rounded-md
                                           hover:bg-gray-100 transition font-semibold text-sm">
                                    Save Grade
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            @empty
                <p class="text-sm text-gray-500">
                    No decisions submitted for this scenario yet.
                </p>
            @endforelse

        </div>
    </div>
</x-app-layout>
