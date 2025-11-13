@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Manage-IT — Scenario List</h1>

        {{-- Create New Scenario button --}}
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

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($scenarios as $s)
            <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition bg-white">
                <div class="flex justify-between items-start">
                    <div class="pr-3">
                        <h2 class="text-xl font-bold">{{ $s->title }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ \Illuminate\Support\Str::limit($s->description, 120) }}
                        </p>
                        <div class="text-xs text-gray-500 mt-2 space-y-1">
                            <p>
                                Budget: £{{ $s->budget }} · Duration: {{ $s->duration }} days · Difficulty: {{ ucfirst($s->difficulty) }}
                            </p>
                            <p>
                                Status:
                                @if($s->isOpenForSubmission())
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
                                Deadline:
                                @if($s->deadline)
                                    {{ $s->deadline->format('Y-m-d H:i') }}
                                    @if(now()->greaterThan($s->deadline))
                                        <span class="text-[10px] text-red-600">(passed)</span>
                                    @endif
                                @else
                                    <span class="text-[11px] text-gray-400">No deadline set</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="text-right space-y-2">

                        {{-- View --}}
                        <a href="{{ route('scenarios.show', $s) }}"
                           class="text-indigo-600 text-sm block">
                            View
                        </a>

                        {{-- Edit + Delete only for creator or admin --}}
                        @if(auth()->check() && (auth()->id() === $s->created_by || auth()->user()->role === 'admin'))
                            <a href="{{ route('scenarios.edit', $s) }}"
                               class="text-orange-500 text-sm block">
                                Edit
                            </a>

                            {{-- Delete Scenario button (same style as Create New Scenario) --}}
                            <form action="{{ route('scenarios.destroy', $s) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this scenario? All related decisions and results may be affected.');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="mt-2 w-full px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500 transition text-sm">
                                    Delete Scenario
                                </button>
                            </form>
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
