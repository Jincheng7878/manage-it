@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Decision Result</h1>

    <div class="border rounded-lg p-4 shadow-sm bg-white">
        <div class="mb-3">
            <div class="text-sm text-gray-500">Scenario</div>
            <div class="font-semibold">
                {{ optional($result->decision->scenario)->title ?? 'N/A' }}
            </div>
        </div>

        <div class="mb-3">
            <div class="text-sm text-gray-500">Submitted by</div>
            <div class="font-semibold">
                {{ optional($result->decision->user)->name ?? 'N/A' }}
                <span class="text-xs text-gray-400 ml-1">
                    ({{ optional($result->decision)->created_at?->toDayDateTimeString() ?? 'Unknown time' }})
                </span>
            </div>
        </div>

        <div class="mb-3">
            <div class="text-sm text-gray-500">Strategy</div>
            <p class="text-gray-800 mt-1">
                {{ optional($result->decision)->strategy ?? 'No strategy text.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <div class="text-xs text-gray-500">Time allocation (days)</div>
                <div class="text-lg font-semibold">
                    {{ optional($result->decision)->time_alloc ?? '-' }}
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Cost allocation (£)</div>
                <div class="text-lg font-semibold">
                    {{ optional($result->decision)->cost_alloc ?? '-' }}
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Risk level</div>
                <div class="text-lg font-semibold capitalize">
                    {{ optional($result->decision)->risk_level ?? '-' }}
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="text-xs text-gray-500">Score</div>
            <div class="text-3xl font-bold text-indigo-600">
                {{ $result->score }} <span class="text-base text-gray-500">/ 100</span>
            </div>
        </div>

        <div class="mb-4">
            <div class="text-xs text-gray-500">Feedback</div>
            <p class="mt-1 text-gray-800">
                {{ $result->feedback ?? 'No feedback available.' }}
            </p>
        </div>

        <div class="text-xs text-gray-400 mt-2">
            Generated at: {{ $result->created_at->toDayDateTimeString() }}
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900">
            ← Back
        </a>
    </div>
</div>
@endsection
