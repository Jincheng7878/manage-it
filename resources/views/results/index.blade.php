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

    {{-- ========== Page Title ========== --}}
    <div class="max-w-7xl mx-auto px-6 mt-10 mb-6 fade">
        <h1 class="text-3xl font-extrabold text-gray-900">
            @if(Auth::user()->role === 'admin')
                All Results
            @else
                My Results
            @endif
        </h1>
        <p class="text-gray-600 mt-1">
            @if(Auth::user()->role === 'admin')
                View all student submissions & scores.
            @else
                Review your performance and feedback.
            @endif
        </p>
    </div>

    {{-- ========== Results List ========== --}}
    <div class="max-w-7xl mx-auto px-6 mb-14 fade">
        <div class="glass p-6 shadow-xl rounded-2xl">

            @if($results->count() === 0)
                <p class="text-gray-600 text-center py-10 text-lg">
                    No results available yet.
                </p>
            @else

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr class="text-left text-gray-700 font-semibold">
                                <th class="py-3">Scenario</th>
                                <th class="py-3">Submitted By</th>
                                <th class="py-3">Score</th>
                                <th class="py-3">Feedback</th>
                                <th class="py-3">Submitted At</th>
                                <th class="py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                        @foreach($results as $result)
                            <tr class="hover:bg-white/40 transition">

                                {{-- Scenario --}}
                                <td class="py-3 font-semibold text-gray-800">
                                    {{ $result->decision->scenario->title }}
                                </td>

                                {{-- User --}}
                                <td class="py-3 text-gray-700">
                                    {{ $result->decision->user->name }}
                                </td>

                                {{-- Score --}}
                                <td class="py-3 text-gray-800 font-bold">
                                    {{ $result->score ?? '—' }}
                                </td>

                                {{-- Feedback --}}
                                <td class="py-3 text-gray-700 max-w-xs truncate">
                                    {{ $result->feedback ?? '—' }}
                                </td>

                                {{-- Submitted time --}}
                                <td class="py-3 text-gray-600 text-sm">
                                    {{ $result->created_at->diffForHumans() }}
                                </td>

                                {{-- View btn --}}
                                <td class="py-3 text-right">
                                    <a href="{{ route('results.show', $result->id) }}"
                                       class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm hover:bg-indigo-500 transition">
                                        View
                                    </a>
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $results->links() }}
                </div>

            @endif

        </div>
    </div>

</x-app-layout>
