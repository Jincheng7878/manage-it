@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">
        Decisions for Scenario: {{ $scenario->title }}
    </h1>

    <a href="{{ route('decisions.create', $scenario) }}"
       class="px-3 py-2 bg-green-600 text-white rounded mb-4 inline-block hover:bg-green-500 transition">
        Submit New Decision
    </a>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-3">
        @foreach($decisions as $d)
            <div class="border rounded p-3 bg-gray-50">
                <div class="flex justify-between gap-4">

                    <div class="flex-1">
                        <div class="text-sm font-semibold">{{ $d->user->name }}</div>
                        <div class="text-sm text-gray-700 mt-1">
                            {{ Str::limit($d->strategy, 180) }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Time: {{ $d->time_alloc }} days ·
                            Cost: £{{ $d->cost_alloc }} ·
                            Risk: {{ ucfirst($d->risk_level) }}
                        </div>

                        @if($d->file_path)
                            <div class="text-xs mt-2">
                                <a href="{{ asset('storage/' . $d->file_path) }}"
                                   class="text-blue-600 underline"
                                   target="_blank">
                                    Download Student File
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="text-right text-sm space-y-2">

                        <div>
                            <span class="text-gray-600">Score: </span>
                            <span class="font-bold">
                                {{ optional($d->result)->score ?? '—' }}
                            </span>
                        </div>

                        @if(optional($d->result)->score)
                            <div>
                                <a href="{{ route('results.show', $d->result) }}"
                                   class="text-indigo-600 text-sm hover:underline">
                                    View Result
                                </a>
                            </div>
                        @endif

                        <div class="text-xs text-gray-500">
                            {{ $d->created_at->diffForHumans() }}
                        </div>

                        {{-- Delete button: only owner or admin --}}
                        @if(auth()->id() === $d->user_id || auth()->user()->role === 'admin')
                            <form method="POST"
                                  action="{{ route('decisions.destroy', $d) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this decision?');">
                                @csrf
                                @method('DELETE')
                                <button class="mt-2 px-3 py-1 text-xs font-semibold bg-red-500 text-white rounded hover:bg-red-600 transition">
                                    Delete
                                </button>
                            </form>
                        @endif

                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $decisions->links() }}
    </div>
</div>
@endsection
