@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">
        Submit Decision for Scenario: {{ $scenario->title }}
    </h1>

    <form method="POST"
          action="{{ route('decisions.store', $scenario) }}"
          enctype="multipart/form-data">
        @csrf

        <!-- Strategy -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Strategy Description</label>
            <textarea name="strategy"
                      class="mt-1 block w-full border rounded p-2"
                      rows="5" required>{{ old('strategy') }}</textarea>
            @error('strategy')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Time / Cost / Risk -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

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

        <!-- Notes -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Notes</label>
            <textarea name="notes"
                      class="mt-1 block w-full border rounded p-2">{{ old('notes') }}</textarea>
        </div>

        <!-- File Upload -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Upload File (optional)</label>
            <input type="file" name="file"
                   class="mt-1 block w-full border rounded p-2">
            @error('file')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Buttons -->
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
