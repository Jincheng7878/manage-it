@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Create New Scenario</h1>

    <form method="POST"
          action="{{ route('scenarios.store') }}"
          enctype="multipart/form-data">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Title</label>
            <input name="title"
                   value="{{ old('title') }}"
                   class="mt-1 block w-full border rounded p-2"
                   required>
            @error('title')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description"
                      class="mt-1 block w-full border rounded p-2"
            >{{ old('description') }}</textarea>
        </div>

        <!-- Budget / Duration / Difficulty -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium">Budget (£)</label>
                <input name="budget" type="number"
                       value="{{ old('budget', 10000) }}"
                       class="mt-1 block w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Duration (days)</label>
                <input name="duration" type="number"
                       value="{{ old('duration', 30) }}"
                       class="mt-1 block w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Difficulty</label>
                <select name="difficulty" class="mt-1 block w-full border rounded p-2">
                    <option value="easy"   {{ old('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ old('difficulty', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard"   {{ old('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
            </div>
        </div>

        <!-- Status + Deadline -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 block w-full border rounded p-2">
                    <option value="open"   {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    If closed, students cannot submit new decisions.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium">Deadline (optional)</label>
                <input type="datetime-local"
                       name="deadline"
                       value="{{ old('deadline') }}"
                       class="mt-1 block w-full border rounded p-2">
                <p class="text-xs text-gray-500 mt-1">
                    After this date/time, submissions will be blocked automatically.
                </p>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Scenario Image (optional)</label>
            <input type="file"
                   name="image"
                   class="mt-1 block w-full border rounded p-2">
            <p class="text-xs text-gray-500 mt-1">
                You can upload a relevant image (project site, diagram, etc.).
            </p>
            @error('image')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- File Upload -->
        <div class="mb-6">
            <label class="block text-sm font-medium">Attachment File (optional)</label>
            <input type="file"
                   name="file"
                   class="mt-1 block w-full border rounded p-2">
            <p class="text-xs text-gray-500 mt-1">
                For example, a PDF brief, Excel data, or additional documentation.
            </p>
            @error('file')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500 transition">
                Save
            </button>

            <a href="{{ route('scenarios.index') }}"
               class="text-gray-600 hover:text-gray-800 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
