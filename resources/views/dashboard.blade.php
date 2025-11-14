@php
    use Illuminate\Support\Facades\Route;
@endphp

<x-app-layout>

    {{-- =============================== --}}
    {{--   Global Style: Glass + Fade    --}}
    {{-- =============================== --}}
    <style>
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(20px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .fade {
            opacity: 0;
            animation: fadeUp .65s ease-out forwards;
        }

        /* Glass effect */
        .glass {
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            background: rgba(255, 255, 255, 0.45);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.28);
        }
    </style>

    {{-- ================================================= --}}
    {{--     Frosted Welcome Area (clean, no gap)          --}}
    {{-- ================================================= --}}
    <div class="w-full bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 pb-10 pt-4">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <div class="glass p-8 shadow-2xl rounded-3xl fade">

                <div class="flex items-start space-x-6">

                    {{-- Avatar/Icon --}}
                    <div class="p-4 bg-white/60 rounded-2xl shadow-md">
                        <svg class="h-12 w-12 text-black/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                  d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM17 14v1a4 4 0 01-4 4H5a4 4 0 01-4-4v-1a4 4 0 014-4h8a4 4 0 014 4z"/>
                        </svg>
                    </div>

                    {{-- Welcome Text --}}
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900">
                            Welcome, {{ Auth::user()->name }} 👋
                        </h2>

                        <p class="mt-2 text-gray-800 text-base leading-relaxed">
                            This is your personalised learning dashboard.<br>
                            You can access all your scenarios, decisions and results below.
                        </p>

                        {{-- Role Badge --}}
                        <span class="inline-block mt-4 px-4 py-1.5 rounded-full text-sm font-semibold
                            {{ Auth::user()->role === 'admin'
                                ? 'bg-purple-200 text-purple-800'
                                : 'bg-green-200 text-green-800' }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- ================================================= --}}
    {{--                  Glass Cards Section              --}}
    {{-- ================================================= --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-8 mt-10 mb-16">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- ===== Scenarios ===== --}}
            <a href="{{ route('scenarios.index') }}"
               class="glass shadow-xl p-6 rounded-2xl fade hover:-translate-y-1 hover:shadow-2xl transition"
               style="animation-delay: .15s;">
                <div class="flex items-center space-x-4">

                    <div class="p-4 bg-indigo-200/60 rounded-xl">
                        <svg class="h-8 w-8 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Scenarios</h3>
                        <p class="text-gray-700 text-sm">View tasks and submit your decisions.</p>
                    </div>

                </div>
            </a>

            {{-- ===== My Decisions ===== --}}
            <a href="{{ route('my.decisions') }}"
               class="glass shadow-xl p-6 rounded-2xl fade hover:-translate-y-1 hover:shadow-2xl transition"
               style="animation-delay: .25s;">
                <div class="flex items-center space-x-4">

                    <div class="p-4 bg-green-200/60 rounded-xl">
                        <svg class="h-8 w-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-900">My Decisions</h3>
                        <p class="text-gray-700 text-sm">Review your submissions.</p>
                    </div>

                </div>
            </a>

            {{-- ===== My Grades ===== --}}
            <a href="{{ route('results.index') }}"
               class="glass shadow-xl p-6 rounded-2xl fade hover:-translate-y-1 hover:shadow-2xl transition"
               style="animation-delay: .35s;">
                <div class="flex items-center space-x-4">

                    <div class="p-4 bg-yellow-200/60 rounded-xl">
                        <svg class="h-8 w-8 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-900">My Grades</h3>
                        <p class="text-gray-700 text-sm">Check your performance.</p>
                    </div>

                </div>
            </a>

            {{-- ===== Admin Only ===== --}}
            @if(Auth::user()->role === 'admin')

                {{-- Analytics ONLY (Grade Decisions removed) --}}
                <a href="{{ route('admin.analytics') }}"
                   class="glass shadow-xl p-6 rounded-2xl fade hover:-translate-y-1 hover:shadow-2xl transition"
                   style="animation-delay: .45s;">
                    <div class="flex items-center space-x-4">

                        <div class="p-4 bg-pink-200/60 rounded-xl">
                            <svg class="h-8 w-8 text-pink-700" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-width="2"
                                      d="M11 17a1 1 0 01-1-1V9a1 1 0 112 0v7a1 1 0 01-1 1zm4 0a1 1 0 
                                       01-1-1V5a1 1 0 112 0v11a1 1 0 01-1 1zm-8 0a1 1 0 
                                       01-1-1v-3a1 1 0 112 0v3a1 1 0 01-1 1z"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Analytics</h3>
                            <p class="text-gray-700 text-sm">Charts & insights for teachers.</p>
                        </div>

                    </div>
                </a>

            @endif

        </div>

    </div>

</x-app-layout>
