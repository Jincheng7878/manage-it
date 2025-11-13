@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            Scenario Details - {{ $scenario->title }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">

                {{-- flash messages --}}
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

                {{-- Debug Role Line (optional,可以保留方便调试) --}}
                <p class="text-xs text-gray-500 mb-3">
                    Logged in as role: <strong>{{ Auth::user()->role }}</strong>
                </p>

                {{-- Scenario Image (if exists) --}}
                @if($scenario->image_path)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $scenario->image_path) }}"
                             alt="Scenario image"
                             class="w-full max-h-80 object-cover rounded-lg shadow-md border">
                    </div>
                @endif

                <div class="flex justify-between items-start mb-6">

                    {{-- Scenario Information --}}
                    <div class="pr-4">
                        <h1 class="text-3xl font-extrabold text-gray-900">{{ $scenario->title }}</h1>

                        <p class="text-gray-700 mt-3 leading-relaxed">
                            {{ $scenario->description }}
                        </p>

                        <div class="text-sm text-gray-600 mt-4 space-y-1">
                            <p><strong>Budget:</strong> £{{ $scenario->budget }}</p>
                            <p><strong>Duration:</strong> {{ $scenario->duration }} days</p>
                            <p><strong>Difficulty:</strong> {{ ucfirst($scenario->difficulty) }}</p>

                            {{-- Status & Deadline --}}
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
                                        <span class="text-xs text-red-600">(passed)</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-500">No deadline set</span>
                                @endif
                            </p>
                        </div>

                        {{-- Scenario Attachment (teacher uploaded file) --}}
                        @if($scenario->file_path)
                            <p class="text-sm mt-4">
                                <strong>Attachment:</strong>
                                <a href="{{ asset('storage/' . $scenario->file_path) }}"
                                   class="text-blue-600 underline"
                                   target="_blank">
                                    Download File
                                </a>
                            </p>
                        @endif
                    </div>

                    {{-- Buttons --}}
                    <div class="text-right space-y-3">

                        {{-- STUDENT BUTTON (only when open) --}}
                        @if($scenario->isOpenForSubmission())
                            <a href="{{ route('decisions.create', $scenario) }}"
                               class="block px-4 py-2 text-center font-semibold text-black
                                      bg-gradient-to-r from-green-300 to-green-100
                                      rounded-xl shadow-md hover:shadow-lg
                                      hover:from-green-200 hover:to-green-50
                                      transform hover:-translate-y-0.5 transition-all duration-200 border border-green-400">
                                Submit Decision
                            </a>
                        @else
                            <div class="block px-4 py-2 text-center text-xs font-semibold text-gray-600
                                        bg-gray-100 rounded-xl border border-gray-300">
                                Submissions are closed for this scenario.
                            </div>
                        @endif

                        {{-- ADMIN BUTTON (Purple Gradient + Black Text) --}}
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('results.gradeList', $scenario) }}"
                               class="block px-4 py-2 text-center font-semibold text-black
                                      bg-gradient-to-r from-purple-300 to-pink-200
                                      rounded-xl shadow-lg hover:shadow-xl
                                      hover:from-purple-200 hover:to-pink-100
                                      transform hover:-translate-y-0.5 transition-all duration-200 border border-purple-400">
                                ⭐ Grade Decisions ⭐
                            </a>
                        @endif

                    </div>
                </div>

                {{-- Section Title --}}
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    Submitted Decisions
                </h2>

                {{-- No Decisions --}}
                @if($scenario->decisions->isEmpty())
                    <p class="text-sm text-gray-500">No decisions submitted yet.</p>

                @else
                    {{-- Decisions List --}}
                    <div class="space-y-4">
                        @foreach($scenario->decisions as $d)
                            <div class="p-4 bg-gray-50 border rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex justify-between">

                                    <div class="pr-4">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $d->user->name }}
                                        </div>

                                        <div class="text-sm text-gray-700 mt-1">
                                            {{ Str::limit($d->strategy, 200) }}
                                        </div>

                                        {{-- Student Attachment --}}
                                        @if($d->file_path)
                                            <p class="text-xs mt-2">
                                                <a href="{{ asset('storage/' . $d->file_path) }}"
                                                   class="text-blue-600 underline"
                                                   target="_blank">
                                                    Download Student File
                                                </a>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="text-right text-sm flex flex-col items-end space-y-2">

                                        <div class="text-gray-700">
                                            <strong>Score:</strong>
                                            <span class="font-extrabold text-indigo-700">
                                                {{ optional($d->result)->score ?? '—' }}
                                            </span>
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $d->created_at->diffForHumans() }}
                                        </div>

                                        {{-- Delete area --}}
                                        @if(auth()->check() && (auth()->id() === $d->user_id || auth()->user()->role === 'admin'))
                                            <form method="POST"
                                                  action="{{ route('decisions.destroy', $d) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this decision?');"
                                                  class="pt-1 border-t border-gray-200 mt-2">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="mt-2 inline-flex items-center px-3 py-1 text-xs font-semibold
                                                           bg-red-500 text-white rounded hover:bg-red-600 transition">
                                                    {{-- Trash icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-3 w-3 mr-1"
                                                         viewBox="0 0 20 20"
                                                         fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                              d="M8.5 3a1.5 1.5 0 00-1.415 1H4.5a.5.5 0 000 1h.54l.7 9.047A2 2 0 007.735 16h4.53a2 2 0 001.995-1.953L14.96 5H15.5a.5.5 0 000-1h-2.585A1.5 1.5 0 0011.5 3h-3zM8 7a.5.5 0 011 0v6a.5.5 0 01-1 0V7zm4 .5a.5.5 0 10-1 0v6a.5.5 0 001 0v-6z"
                                                              clip-rule="evenodd" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
