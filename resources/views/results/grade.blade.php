<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Grade Decisions – {{ $scenario->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Scenario Description --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-2">Scenario Overview</h3>
                <p class="text-gray-700">{{ $scenario->description }}</p>
                <div class="text-xs text-gray-500 mt-2">
                    Budget: £{{ $scenario->budget }} · Duration: {{ $scenario->duration }} days · Difficulty: {{ ucfirst($scenario->difficulty) }}
                </div>
            </div>

            {{-- Decisions List --}}
            <h3 class="text-lg font-semibold mb-3">Student Decisions</h3>

            @foreach($scenario->decisions as $decision)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">
                    <div class="flex justify-between items-start">

                        {{-- Student Decision --}}
                        <div class="max-w-xl">
                            <div class="text-sm font-semibold text-gray-800">
                                Submitted by: {{ $decision->user->name }}
                            </div>

                            <div class="text-sm text-gray-700 mt-2">
                                <span class="font-medium">Strategy:</span><br>
                                {{ $decision->strategy }}
                            </div>

                            <div class="text-xs text-gray-500 mt-3">
                                Submitted: {{ $decision->created_at->diffForHumans() }}
                            </div>
                        </div>

                        {{-- Grading Form --}}
                        <form action="{{ route('results.grade', $decision) }}" method="POST" class="w-64">
                            @csrf

                            <label class="block text-sm font-medium text-gray-800 mb-1">Score</label>
                            <input type="number"
                                   name="score"
                                   min="0"
                                   max="100"
                                   value="{{ $decision->result->score ?? '' }}"
                                   class="w-full border rounded p-2 mb-3 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>

                            <label class="block text-sm font-medium text-gray-800 mb-1">Feedback</label>
                            <textarea name="feedback"
                                      rows="3"
                                      class="w-full border rounded p-2 mb-3 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Write teacher feedback here...">{{ $decision->result->feedback ?? '' }}</textarea>

                            <!-- Beautiful Save Grade Button (black text + gradient) -->
                            <button
                                class="w-full text-black font-semibold py-2 rounded-xl
                                       bg-gradient-to-r from-purple-300 to-pink-200
                                       border border-purple-400
                                       shadow-md hover:shadow-lg
                                       hover:from-purple-200 hover:to-pink-100
                                       transform hover:-translate-y-0.5
                                       transition-all duration-200">
                                ⭐ Save Grade ⭐
                            </button>

                        </form>

                    </div>
                </div>
            @endforeach

            @if($scenario->decisions->isEmpty())
                <p class="text-gray-500 text-sm">No decisions submitted for this scenario yet.</p>
            @endif

        </div>
    </div>
</x-app-layout>
