@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Edit Scenario</h1>

    <form method="POST"
          action="{{ route('scenarios.update', $scenario) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Title</label>
            <input name="title"
                   value="{{ old('title', $scenario->title) }}"
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
            >{{ old('description', $scenario->description) }}</textarea>
        </div>

        <!-- Budget / Duration / Difficulty -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

            <div>
                <label class="block text-sm font-medium">Budget (£)</label>
                <input name="budget"
                       type="number"
                       value="{{ old('budget', $scenario->budget) }}"
                       class="mt-1 block w-full border rounded p-2"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium">Duration (days)</label>
                <input name="duration"
                       type="number"
                       value="{{ old('duration', $scenario->duration) }}"
                       class="mt-1 block w-full border rounded p-2"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium">Difficulty</label>
                <select name="difficulty" class="mt-1 block w-full border rounded p-2">
                    <option value="easy"   @selected(old('difficulty', $scenario->difficulty) === 'easy')>Easy</option>
                    <option value="medium" @selected(old('difficulty', $scenario->difficulty) === 'medium')>Medium</option>
                    <option value="hard"   @selected(old('difficulty', $scenario->difficulty) === 'hard')>Hard</option>
                </select>
            </div>

        </div>

        <!-- Status + Deadline -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 block w-full border rounded p-2">
                    <option value="open"   @selected(old('status', $scenario->status ?? 'open') === 'open')>Open</option>
                    <option value="closed" @selected(old('status', $scenario->status ?? 'open') === 'closed')>Closed</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    If closed, students cannot submit new decisions.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium">Deadline (optional)</label>
                <input type="datetime-local"
                       name="deadline"
                       value="{{ old('deadline', optional($scenario->deadline)->format('Y-m-d\TH:i')) }}"
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
            @if($scenario->image_path)
                <p class="text-xs text-gray-500 mt-1">
                    Current image:
                    <a href="{{ asset('storage/' . $scenario->image_path) }}"
                       target="_blank"
                       class="underline text-indigo-600">
                        View
                    </a>
                </p>
            @endif
            @error('image')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- File Upload -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Attachment File (optional)</label>
            <input type="file"
                   name="file"
                   class="mt-1 block w-full border rounded p-2">
            @if($scenario->file_path)
                <p class="text-xs text-gray-500 mt-1">
                    Current file:
                    <a href="{{ asset('storage/' . $scenario->file_path) }}"
                       target="_blank"
                       class="underline text-indigo-600">
                        Download
                    </a>
                </p>
            @endif
            @error('file')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Video File -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Video File (optional)</label>
            <input type="file"
                   name="video"
                   class="mt-1 block w-full border rounded p-2"
                   accept="video/*">
            @if($scenario->video_path)
                <p class="text-xs text-gray-500 mt-1">
                    Current video file:
                    <a href="{{ asset('storage/' . $scenario->video_path) }}"
                       target="_blank"
                       class="underline text-indigo-600">
                        Download / Open
                    </a>
                </p>
            @endif
            @error('video')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Video URL -->
        <div class="mb-6">
            <label class="block text-sm font-medium">Video URL (optional)</label>
            <input type="text"
                   name="video_url"
                   value="{{ old('video_url', $scenario->video_url) }}"
                   class="mt-1 block w-full border rounded p-2"
                   placeholder="https://youtu.be/xxxx or https://www.youtube.com/watch?v=xxxx">
            @if($scenario->video_url)
                <p class="text-xs text-gray-500 mt-1">
                    Current URL:
                    <a href="{{ $scenario->video_url }}"
                       target="_blank"
                       class="underline text-indigo-600">
                        Open
                    </a>
                </p>
            @endif
            @error('video_url')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500 transition">
                Save
            </button>

            <a href="{{ route('scenarios.show', $scenario) }}"
               class="text-gray-600 hover:text-gray-800 transition">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
