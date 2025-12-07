@php
    use Illuminate\Support\Str;

    // 防御性处理，避免空关系出错
    $decision  = $result->decision ?? null;
    $scenario  = $decision->scenario ?? null;
    $student   = $decision->user ?? null;

    $score     = $result->score;              // 可能为 null
    $feedback  = $result->feedback;          // 可能为 null
    $strategy  = $decision->strategy ?? null;
    $createdAt = $result->created_at ?? null;

    $scenarioTitle   = $scenario->title ?? 'Unknown Scenario';
    $difficulty      = $scenario->difficulty ?? null;
    $difficultyLabel = $difficulty ? ucfirst($difficulty) : 'N/A';

    // ====== 简单的“结局等级”划分，用来生成不同风格的故事 ======
    $band = 'unknown';
    if (is_numeric($score)) {
        if ($score >= 80)      $band = 'excellent';
        elseif ($score >= 60)  $band = 'good';
        elseif ($score >= 40)  $band = 'mixed';
        else                   $band = 'poor';
    }

    // 结局标题 + 描述
    switch ($band) {
        case 'excellent':
            $outcomeTitle = 'Outstanding Project Success';
            $outcomeSummary = 'Your decisions kept the project on track in terms of time, cost, and team performance. Stakeholders are highly satisfied and willing to work with you again.';
            $riskText = 'Risks were identified early and mitigated quickly. Minor issues occurred but were handled without major schedule or budget impact.';
            $lessonText = 'Continue using structured decision-making, monitoring key metrics regularly, and communicating proactively with your team and stakeholders.';
            $badgeClass = 'bg-green-100 text-green-800';
            break;

        case 'good':
            $outcomeTitle = 'Solid Project Delivery';
            $outcomeSummary = 'The project reached most of its objectives. There were some trade-offs, but overall the outcome is positive and acceptable to stakeholders.';
            $riskText = 'Some risks were handled reactively instead of proactively. A few delays or cost increments appeared, but you kept things under control.';
            $lessonText = 'Improve early risk identification and contingency planning. Small improvements in communication and prioritisation could lift future scores.';
            $badgeClass = 'bg-blue-100 text-blue-800';
            break;

        case 'mixed':
            $outcomeTitle = 'Partially Successful Project';
            $outcomeSummary = 'The project delivered some value but missed certain goals in time, cost, or quality. Stakeholders see potential, but also clear improvement areas.';
            $riskText = 'Key risks materialised and were not fully mitigated, causing budget pressure or schedule slippage. Some decisions had unintended side effects.';
            $lessonText = 'Reflect on which decisions had the biggest negative impact. In future, consider simulating alternatives before committing to a risky path.';
            $badgeClass = 'bg-yellow-100 text-yellow-800';
            break;

        case 'poor':
            $outcomeTitle = 'Project in Serious Trouble';
            $outcomeSummary = 'The project struggled to meet its objectives. Deadlines, costs, or team morale were significantly impacted by several critical decisions.';
            $riskText = 'High-impact risks were not identified or mitigated in time. Important signals may have been ignored, leading to cascading issues.';
            $lessonText = 'Use this result as a safe opportunity to learn. In real projects, early escalation, transparent communication, and structured planning are essential.';
            $badgeClass = 'bg-red-100 text-red-800';
            break;

        default:
            $outcomeTitle = 'No Score Yet';
            $outcomeSummary = 'This decision has not been graded yet. Once a teacher provides a score, a full outcome summary will appear here.';
            $riskText = 'Outcome analysis is only available after grading.';
            $lessonText = 'Wait for grading, then revisit this page to reflect on your result.';
            $badgeClass = 'bg-gray-100 text-gray-800';
            break;
    }
@endphp

<x-app-layout>

    {{-- ========== Header ========== --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            Result Detail – {{ $scenarioTitle }}
        </h2>
    </x-slot>

    {{-- ========== Styles (玻璃拟态 + 动画) ========== --}}
    <style>
        .glass {
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            background: rgba(255, 255, 255, 0.75);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .fade {
            animation: fadeUp .55s ease-out forwards;
        }
    </style>

    <div class="py-8 bg-gray-100">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ========== Top Info Row ========== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade">

                {{-- 左侧：场景 + 学生信息 --}}
                <div class="glass shadow-xl p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-extrabold text-gray-900">
                                {{ $scenarioTitle }}
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">
                                Scenario difficulty:
                                <span class="font-semibold">
                                    {{ $difficultyLabel }}
                                </span>
                            </p>
                        </div>

                        {{-- 难度 badge --}}
                        @if($difficulty)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                         @if($difficulty === 'easy') bg-green-100 text-green-800
                                         @elseif($difficulty === 'medium') bg-yellow-100 text-yellow-800
                                         @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($difficulty) }}
                            </span>
                        @endif
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-2 text-sm text-gray-700">
                        <p>
                            <span class="font-semibold">Student:</span>
                            {{ $student->name ?? 'Unknown student' }}
                        </p>
                        <p>
                            <span class="font-semibold">Decision Submitted At:</span>
                            {{ optional($decision->created_at ?? null)->format('Y-m-d H:i') ?? 'N/A' }}
                        </p>
                        <p>
                            <span class="font-semibold">Result Updated At:</span>
                            {{ optional($result->updated_at ?? $result->created_at)->diffForHumans() ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- 学生策略简介 --}}
                    @if($strategy)
                        <div class="mt-5">
                            <h3 class="text-sm font-semibold text-gray-800 mb-1">
                                Submitted Strategy (summary)
                            </h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ Str::limit($strategy, 320) }}
                            </p>

                            {{-- 如果策略很长，加个提示 --}}
                            @if(Str::length($strategy) > 320)
                                <p class="text-xs text-gray-400 mt-1">
                                    (Full strategy can be viewed in the Decisions section.)
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- 学生上传的文件 --}}
                    @if(!empty($decision?->file_path))
                        <div class="mt-4 text-sm">
                            <span class="font-semibold text-gray-800">Student Attachment:</span>
                            <a href="{{ asset('storage/'.$decision->file_path) }}"
                               class="text-indigo-600 underline ml-1"
                               target="_blank">
                                Download File
                            </a>
                        </div>
                    @endif
                </div>

                {{-- 右侧：分数 + 反馈 + 简要标签 --}}
                <div class="glass shadow-xl p-6">

                    {{-- 分数 --}}
                    <div class="flex items-baseline justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                                Score
                            </h3>
                            <div class="mt-2 flex items-baseline">
                                <span class="text-4xl font-extrabold text-gray-900">
                                    {{ is_null($score) ? '—' : $score }}
                                </span>
                                @if(is_numeric($score))
                                    <span class="ml-2 text-gray-500 font-medium">/ 100</span>
                                @endif
                            </div>
                        </div>

                        {{-- 结局等级 badge --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            @if($band === 'unknown')
                                Awaiting grading
                            @elseif($band === 'excellent')
                                Excellent outcome
                            @elseif($band === 'good')
                                Good outcome
                            @elseif($band === 'mixed')
                                Mixed outcome
                            @else
                                Project in trouble
                            @endif
                        </span>
                    </div>

                    {{-- 教师反馈 --}}
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-1">
                            Teacher Feedback
                        </h3>
                        @if($feedback)
                            <div class="mt-2 p-3 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 leading-relaxed">
                                {{ $feedback }}
                            </div>
                        @else
                            <p class="text-sm text-gray-500 mt-2">
                                No written feedback has been added yet.
                            </p>
                        @endif
                    </div>

                    {{-- 返回按钮 --}}
                    <div class="mt-6 flex justify-between items-center text-sm">
                        <a href="{{ route('results.index') }}"
                           class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300
                                  text-gray-700 hover:bg-gray-50 transition">
                            ← Back to results list
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('results.gradeList', $scenario) }}"
                               class="inline-flex items-center px-3 py-2 rounded-lg
                                      bg-purple-600 text-white hover:bg-purple-500 transition">
                                Grade this scenario
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ========== Outcome Story（项目结局生成器区域） ========== --}}
            <div class="glass shadow-xl p-6 fade">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Project Outcome Story
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            This section turns your score into a narrative summary to help you reflect on the result.
                        </p>
                    </div>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                        Scenario: {{ $scenarioTitle }}
                    </span>
                </div>

                {{-- 三个小卡片：结局 / 风险 / 经验 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

                    {{-- 1. Outcome Summary --}}
                    <div class="bg-white/70 rounded-2xl border border-gray-200 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">
                            1. Outcome Summary
                        </h3>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $outcomeSummary }}
                        </p>
                    </div>

                    {{-- 2. Key Risks / Issues --}}
                    <div class="bg-white/70 rounded-2xl border border-gray-200 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">
                            2. Key Risks & Issues
                        </h3>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $riskText }}
                        </p>
                    </div>

                    {{-- 3. Lessons for Next Time --}}
                    <div class="bg-white/70 rounded-2xl border border-gray-200 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">
                            3. Lessons for Next Time
                        </h3>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $lessonText }}
                        </p>
                    </div>

                </div>

                {{-- 如果有分数，就再给一个小总结行 --}}
                @if(is_numeric($score))
                    <p class="mt-5 text-xs text-gray-500">
                        This story is auto-generated based on your score ({{ $score }}/100) and the scenario difficulty ({{ $difficultyLabel }}).
                        In your report, you can screenshot or summarise this section as “Outcome Reflection”.
                    </p>
                @else
                    <p class="mt-5 text-xs text-gray-500">
                        Once a teacher grades this decision, this outcome story will update automatically based on the score.
                    </p>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
