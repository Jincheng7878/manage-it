<x-app-layout>

    <style>
        .glass {
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            background: rgba(255, 255, 255, 0.55);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .fade {
            animation: fadeUp 0.5s ease-out forwards;
        }
    </style>

    {{-- Title --}}
    <div class="max-w-7xl mx-auto px-6 mt-10 mb-6 fade">
        <h1 class="text-3xl font-extrabold text-gray-900">My Decisions</h1>
        <p class="text-gray-600 mt-1">Review all decisions you have submitted.</p>
    </div>

    {{-- List --}}
    <div class="max-w-7xl mx-auto px-6 mb-14 fade">
        <div class="glass p-6 shadow-xl rounded-2xl">

            @if($decisions->count() === 0)
                <p class="text-gray-600 text-center py-10 text-lg">
                    You have not submitted any decisions yet.
                </p>
            @else

            <div class="space-y-4">

                @foreach($decisions as $d)
                <div class="p-4 rounded-xl bg-white/60 glass hover:bg-white/80 transition">

                    <div class="flex justify-between items-start">

                        {{-- Left content --}}
                        <div class="w-3/4">

                            <h3 class="font-bold text-gray-900 text-lg">
                                {{ $d->scenario->title }}
                            </h3>

                            <p class="text-gray-700 mt-1">
                                <strong>Strategy:</strong>
                                {{ \Illuminate\Support\Str::limit($d->strategy, 150) }}
                            </p>

                            @if($d->file_path)
                                <p class="mt-2">
                                    <a href="{{ asset('storage/' . $d->file_path) }}" 
                                       class="text-blue-600 underline text-sm"
                                       target="_blank">
                                        Download Attachment
                                    </a>
                                </p>
                            @endif

                            <p class="text-gray-500 text-sm mt-2">
                                Submitted: {{ $d->created_at->diffForHumans() }}
                            </p>

                        </div>

                        {{-- Right content --}}
                        <div class="text-right space-y-3">

                            {{-- Score --}}
                            <div class="text-lg font-bold text-indigo-700">
                                Score:
                                <span>
                                    {{ optional($d->result)->score ?? 'Not graded' }}
                                </span>
                            </div>

                            {{-- View result link if scored --}}
                            @if($d->result)
                                <a href="{{ route('results.show', $d->result->id) }}"
                                   class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm hover:bg-indigo-500 transition">
                                   View Result
                                </a>
                            @endif

                        </div>

                    </div>

                </div>
                @endforeach

            </div>

            <div class="mt-6">
                {{ $decisions->links() }}
            </div>

            @endif

        </div>
    </div>

</x-app-layout>
