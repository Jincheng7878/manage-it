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

                {{-- Debug Role Line (can be removed if not needed) --}}
                <p class="text-xs text-gray-500 mb-3">
                    Logged in as role: <strong>{{ Auth::user()->role }}</strong>
                </p>

                {{-- Scenario Image --}}
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
                        <h1 class="text-3xl font-extrabold text-gray-900">
                            {{ $scenario->title }}
                        </h1>

                        <p class="text-gray-700 mt-3 leading-relaxed">
                            {{ $scenario->description }}
                        </p>

                        <div class="text-sm text-gray-600 mt-4 space-y-1">
                            <p><strong>Budget:</strong> £{{ $scenario->budget }}</p>
                            <p><strong>Duration:</strong> {{ $scenario->duration }} days</p>
                            <p><strong>Difficulty:</strong> {{ ucfirst($scenario->difficulty) }}</p>
                        </div>

                        {{-- Teacher Attachment --}}
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

                    {{-- Right Side Buttons --}}
                    <div class="text-right space-y-3">

                        {{-- Submit Decision --}}
                        <a href="{{ route('decisions.create', $scenario) }}"
                           class="block px-4 py-2 text-center font-semibold text-black
                                  bg-gradient-to-r from-green-300 to-green-100
                                  rounded-xl shadow-md hover:shadow-lg
                                  hover:from-green-200 hover:to-green-50
                                  transform hover:-translate-y-0.5 transition-all duration-200
                                  border border-green-400">
                            Submit Decision
                        </a>

                        {{-- Grade Decisions (admin only) --}}
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('results.gradeList', $scenario) }}"
                               class="block px-4 py-2 text-center font-semibold text-black
                                      bg-gradient-to-r from-purple-300 to-pink-200
                                      rounded-xl shadow-lg hover:shadow-xl
                                      hover:from-purple-200 hover:to-pink-100
                                      transform hover:-translate-y-0.5 transition-all duration-200
                                      border border-purple-400">
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

                                {{-- Top part: content + score/time --}}
                                <div class="flex justify-between items-start">

                                    {{-- Left: Decision info --}}
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

                                    {{-- Right: Score + Time --}}
                                    <div class="text-right text-sm min-w-[130px]">
                                        <div class="text-gray-700">
                                            <strong>Score:</strong>
                                            <span class="font-extrabold text-indigo-700">
                                                {{ optional($d->result)->score ?? '—' }}
                                            </span>
                                        </div>

                                        <div class="text-xs text-gray-500 mt-2">
                                            {{ $d->created_at->diffForHumans() }}
                                        </div>
                                    </div>

                                </div>

                                {{-- Bottom footer area: dedicated for Delete button --}}
                                <div class="mt-3 pt-3 border-t border-gray-200 flex justify-end">

                                    @if(auth()->check() && (auth()->id() === $d->user_id || auth()->user()->role === 'admin'))
                                        <form method="POST"
                                              action="{{ route('decisions.destroy', $d) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this decision?');">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="px-3 py-1 text-xs font-semibold
                                                       flex items-center gap-1
                                                       bg-red-600 text-white rounded-md
                                                       hover:bg-red-500
                                                       transition-colors duration-200">
                                                🗑 Delete
                                            </button>
                                        </form>
                                    @else
                                        {{-- Even if no delete, keep this footer height consistent --}}
                                        <div class="h-1"></div>
                                    @endif

                                </div>

                            </div>
                        @endforeach

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>
