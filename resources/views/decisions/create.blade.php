@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">
        Submit Decision for Scenario: {{ $scenario->title }}
    </h1>

    <form method="POST"
          action="{{ route('decisions.store', $scenario) }}"
          enctype="multipart/form-data">
        @csrf

        {{-- Core strategy --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Strategy Description</label>
            <textarea name="strategy"
                      class="mt-1 block w-full border rounded p-2"
                      rows="4"
                      required>{{ old('strategy') }}</textarea>
            @error('strategy')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <p class="text-xs text-gray-500 mt-1">
                Briefly describe your overall management strategy for this scenario.
            </p>
        </div>

        {{-- Time / Cost / Risk --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium">Time Allocation (days)</label>
                <input name="time_alloc"
                       type="number"
                       value="{{ old('time_alloc', $scenario->duration) }}"
                       class="mt-1 block w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Cost Allocation (£)</label>
                <input name="cost_alloc"
                       type="number"
                       value="{{ old('cost_alloc', $scenario->budget) }}"
                       class="mt-1 block w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Risk Level</label>
                <select name="risk_level" class="mt-1 block w-full border rounded p-2">
                    <option value="low"    {{ old('risk_level') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('risk_level', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high"   {{ old('risk_level') === 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
        </div>

        {{-- Notes --}}
        <div class="mb-6">
            <label class="block text-sm font-medium">Additional Notes (optional)</label>
            <textarea name="notes"
                      class="mt-1 block w-full border rounded p-2"
                      rows="3">{{ old('notes') }}</textarea>
        </div>

        {{-- ---------- SWOT Analysis ---------- --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">SWOT Analysis</h2>
            <p class="text-xs text-gray-500 mb-3">
                Use this section to structure your decision using a SWOT framework.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Strengths</label>
                    <textarea name="swot_strengths"
                              class="mt-1 block w-full border rounded p-2"
                              rows="3"
                    >{{ old('swot_strengths') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Weaknesses</label>
                    <textarea name="swot_weaknesses"
                              class="mt-1 block w-full border rounded p-2"
                              rows="3"
                    >{{ old('swot_weaknesses') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Opportunities</label>
                    <textarea name="swot_opportunities"
                              class="mt-1 block w-full border rounded p-2"
                              rows="3"
                    >{{ old('swot_opportunities') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Threats</label>
                    <textarea name="swot_threats"
                              class="mt-1 block w-full border rounded p-2"
                              rows="3"
                    >{{ old('swot_threats') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ---------- Work Breakdown Structure (WBS) ---------- --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Work Breakdown Structure (WBS)</h2>
            <p class="text-xs text-gray-500 mb-2">
                You can list key work packages and tasks here (e.g. 1.1, 1.2, 2.1...).
            </p>
            <textarea name="wbs"
                      class="mt-1 block w-full border rounded p-2"
                      rows="4"
                      placeholder="1. Initiation: ...&#10;2. Planning: ...&#10;3. Execution: ...&#10;4. Monitoring & Control: ...&#10;5. Closure: ...">{{ old('wbs') }}</textarea>
        </div>

        {{-- ---------- Risk Matrix ---------- --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Risk Matrix</h2>
            <p class="text-xs text-gray-500 mb-2">
                Summarise main risks, their probability, impact and mitigation actions.
            </p>
            <textarea name="risk_matrix"
                      class="mt-1 block w-full border rounded p-2"
                      rows="4"
                      placeholder="Risk 1: ... (Likelihood: High, Impact: Medium, Mitigation: ...)&#10;Risk 2: ...">{{ old('risk_matrix') }}</textarea>
        </div>

        {{-- ---------- Cost Breakdown ---------- --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Cost Breakdown</h2>
            <p class="text-xs text-gray-500 mb-2">
                Provide a high-level cost breakdown for key items or phases.
            </p>
            <textarea name="cost_breakdown"
                      class="mt-1 block w-full border rounded p-2"
                      rows="4"
                      placeholder="Labour: £...&#10;Equipment: £...&#10;Training: £...&#10;Contingency: £...">{{ old('cost_breakdown') }}</textarea>
        </div>

        {{-- Optional attachment --}}
        <div class="mb-6">
            <label class="block text-sm font-medium">
                Upload Supporting File (optional)
            </label>
            <input type="file"
                   name="file"
                   class="mt-1 block w-full border rounded p-2">
            @error('file')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <p class="text-xs text-gray-500 mt-1">
                You can upload an additional document, spreadsheet or diagram to support your decision.
            </p>
        </div>

        {{-- Buttons --}}
        <div class="mt-4 flex gap-3">
            <button
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500 transition">
                Submit Decision
            </button>

            <a href="{{ route('scenarios.show', $scenario) }}"
               class="text-gray-600 hover:text-gray-800 transition">
                Back
            </a>
        </div>

    </form>
</div>
@endsection
