@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Manage-IT — Scenario List</h1>

        <a href="{{ route('scenarios.create') }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500 transition">
            Create New Scenario
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($scenarios as $s)
            <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">

                    {{-- Scenario Info --}}
                    <div>
                        <h2 class="text-xl font-bold">{{ $s->title }}</h2>

                        <p class="text-sm text-gray-600 mt-1">
                            {{ Str::limit($s->description, 120) }}
                        </p>

                        <div class="text-xs text-gray-500 mt-2">
                            Budget: £{{ $s->budget }} · 
                            Duration: {{ $s->duration }} days · 
                            Difficulty: {{ ucfirst($s->difficulty) }}
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="text-right">

                        <a href="{{ route('scenarios.show', $s) }}" 
                           class="text-indigo-600 hover:text-indigo-800">
                            View
                        </a>

                        @if(auth()->id() === $s->created_by || auth()->user()->role === 'admin')
                            <a href="{{ route('scenarios.edit', $s) }}" 
                               class="ml-2 text-orange-500 hover:text-orange-600">
                                Edit
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $scenarios->links() }}
    </div>
</div>
@endsection
