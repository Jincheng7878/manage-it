@php
    use Illuminate\Support\Facades\Route;

    $user      = Auth::user();
    $badges    = $user->badges ?? [];
    $badgesText = implode(' · ', $badges);
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

        .glass {
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            background: rgba(255, 255, 255, 0.45);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        /* ====== 折叠欢迎卡片 ====== */
        .welcome-shell {
            max-height: 96px;              /* 默认只显示一小块 */
            overflow: hidden;
            cursor: pointer;
            position: relative;
            transition:
                max-height 0.5s ease,
                box-shadow 0.3s ease,
                transform 0.3s ease;
        }

        .welcome-shell:hover {
            max-height: 620px;             /* 展开高度，足够容纳全部内容 */
            box-shadow: 0 25px 45px rgba(15, 23, 42, 0.35);
            transform: translateY(-2px);
        }

        /* 底部的渐变遮罩，收起时有，展开时消失 */
        .welcome-shell::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 28px;
            background: linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0),
                rgba(255, 255, 255, 0.96)
            );
            opacity: 1;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .welcome-shell:hover::after {
            opacity: 0;
        }

        /* “Hover to expand” 小提示 */
        .welcome-hint {
            position: absolute;
            right: 1.5rem;
            bottom: 0.4rem;
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(31, 41, 55, 0.75);
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .welcome-shell:hover .welcome-hint {
            opacity: 0;
        }
    </style>

    {{-- ================================================= --}}
    {{--     Frosted Welcome Area (collapsible glass)      --}}
    {{-- ================================================= --}}
    <div class="w-full bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 pb-10 pt-4">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- ⭐ 这个整体变成可折叠卡片，鼠标移上去自动展开 --}}
            <div class="glass welcome-shell shadow-2xl rounded-3xl fade">

                <div class="flex flex-col md:flex-row md:items-start md:space-x-6 space-y-4 md:space-y-0 px-2 md:px-0 py-4 md:py-6">

                    {{-- Avatar/Icon --}}
                    <div class="p-4 bg-white/60 rounded-2xl shadow-md self-start ml-1 md:ml-2">
                        <svg class="h-12 w-12 text-black/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                  d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM17 14v1a4 4 0 01-4 4H5a4 4 0 01-4-4v-1a4 4 0 014-4h8a4 4 0 014 4z"/>
                        </svg>
                    </div>

                    {{-- Welcome Text + Reward Info --}}
                    <div class="flex-1 pr-8 md:pr-10">
                        <h2 class="text-3xl font-extrabold text-gray-900">
                            Welcome, {{ $user->name }} 👋
                        </h2>

                        <p class="mt-2 text-gray-800 text-base leading-relaxed">
                            This is your personalised learning dashboard.<br>
                            You can access all your scenarios, decisions and results below.
                        </p>

                        {{-- Role Badge --}}
                        <div class="mt-3">
                            <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold
                                {{ $user->role === 'admin'
                                    ? 'bg-purple-200 text-purple-800'
                                    : 'bg-green-200 text-green-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        {{-- ⭐ Reward Info: XP / Level / Badges --}}
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">

                            <div class="px-3 py-2 rounded-xl bg-white/60 border border-white/70">
                                <div class="text-xs text-gray-500 uppercase tracking-wide">XP</div>
                                <div class="mt-1 text-lg font-bold text-gray-900">
                                    {{ $user->xp ?? 0 }}
                                </div>
                            </div>

                            <div class="px-3 py-2 rounded-xl bg-white/60 border border-white/70">
                                <div class="text-xs text-gray-500 uppercase tracking-wide">Level</div>
                                <div class="mt-1 text-lg font-bold text-gray-900">
                                    {{ $user->level }}
                                    <span class="ml-2 text-xs text-gray-500">
                                        ({{ $user->xp_to_next_level }} XP to next)
                                    </span>
                                </div>
                            </div>

                            <div class="px-3 py-2 rounded-xl bg-white/60 border border-white/70">
                                <div class="text-xs text-gray-500 uppercase tracking-wide">Badges</div>
                                <div class="mt-1 text-xs text-gray-800 leading-snug">
                                    {{ $badgesText ?: 'Rookie' }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- 小提示条：悬停展开 --}}
                <div class="welcome-hint">
                    HOVER TO EXPAND ▲
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
            @if($user->role === 'admin')

                {{-- Analytics --}}
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
