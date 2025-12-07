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
                My Grades
            @endif
        </h1>
        <p class="text-gray-600 mt-1">
            @if(Auth::user()->role === 'admin')
                View all student submissions & scores, and open each detailed outcome page.
            @else
                Review your scores and open the detailed outcome page for each scenario.
            @endif
        </p>
    </div>

    {{-- ========== Results List ========== --}}
    <div class="max-w-7xl mx-auto px-6 mb-14 fade">
        <div class="glass p-6 shadow-xl rounded-2xl">

            @if($results->isEmpty())
                <p class="text-gray-600 text-center py-10 text-lg">
                    No results available yet.
                </p>
            @else

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 text-sm">
                        <thead>
                            <tr class="text-left text-gray-700 font-semibold">
                                <th class="py-3 pr-4">Scenario</th>

                                {{-- 管理员才显示 Submitted By 列 --}}
                                @if(Auth::user()->role === 'admin')
                                    <th class="py-3 pr-4">Submitted By</th>
                                @endif

                                <th class="py-3 pr-4">Score</th>
                                <th class="py-3 pr-4">Feedback</th>
                                <th class="py-3 pr-4">Graded At</th>
                                <th class="py-3 pl-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                        @foreach($results as $result)
                            <tr class="hover:bg-white/50 transition">

                                {{-- Scenario --}}
                                <td class="py-3 pr-4 font-semibold text-gray-800">
                                    {{ optional(optional($result->decision)->scenario)->title ?? 'Unknown scenario' }}
                                </td>

                                {{-- Submitted By（只有 admin 看得到） --}}
                                @if(Auth::user()->role === 'admin')
                                    <td class="py-3 pr-4 text-gray-700">
                                        {{ optional(optional($result->decision)->user)->name ?? 'Unknown user' }}
                                    </td>
                                @endif

                                {{-- Score --}}
                                <td class="py-3 pr-4 text-gray-800 font-bold">
                                    {{ $result->score ?? '—' }}
                                </td>

                                {{-- Feedback（截断显示） --}}
                                <td class="py-3 pr-4 text-gray-700 max-w-xs truncate">
                                    {{ $result->feedback ?? '—' }}
                                </td>

                                {{-- Graded At --}}
                                <td class="py-3 pr-4 text-gray-600 text-xs">
                                    {{ optional($result->updated_at ?? $result->created_at)->diffForHumans() }}
                                </td>

                                {{-- View 按钮：进入结果详情（有“项目结局生成器”） --}}
                                <td class="py-3 pl-4 text-right">
                                    <a href="{{ route('results.show', $result) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-xl
                                              bg-indigo-600 text-white text-xs font-semibold
                                              hover:bg-indigo-500 transition">
                                        View details
                                    </a>
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- Pagination（如果控制器里用的是 paginate） --}}
                <div class="mt-6">
                    {{ $results->links() }}
                </div>

            @endif

        </div>
    </div>

</x-app-layout>
