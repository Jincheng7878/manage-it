<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Scenario;
use App\Models\Decision;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        // ---------- Summary stats ----------
        $totalScenarios  = Scenario::count();
        $totalUsers      = User::count();
        $totalDecisions  = Decision::count();
        $averageScore    = Result::avg('score');

        // Pending decisions: decisions with no result
        $pendingDecisionsCount = Decision::doesntHave('result')->count();

        // ---------- Recent decisions ----------
        $recentDecisions = Decision::with(['user', 'scenario', 'result'])
            ->latest()
            ->take(8)
            ->get();

        // ---------- Ungraded decisions ----------
        $ungradedDecisions = Decision::with(['user', 'scenario'])
            ->doesntHave('result')
            ->latest()
            ->take(8)
            ->get();

        // ---------- Line chart: decisions per day (last 7 days) ----------
        $fromDate = Carbon::now()->subDays(6)->startOfDay();

        $decisionsPerDay = Decision::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $fromDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->toDateString();
            $chartLabels[] = Carbon::now()->subDays($i)->format('M d');

            $record = $decisionsPerDay->firstWhere('date', $day);
            $chartData[] = $record ? $record->count : 0;
        }

        // ---------- Bar chart: average score per scenario ----------
        // 基于 Result（已经评分的决策），按 scenario 分组
        $allGradedResults = Result::with('decision.scenario')->get();

        // 每个场景的 Result 集合
        $groupedByScenario = $allGradedResults->groupBy(function ($result) {
            return optional($result->decision)->scenario_id;
        });

        $scenarioLabels     = collect();
        $scenarioAvgScores  = collect();
        $scenarioGradeCount = collect();

        foreach ($groupedByScenario as $scenarioId => $results) {
            if (!$scenarioId) {
                continue; // 防止脏数据
            }

            /** @var \App\Models\Result $first */
            $first   = $results->first();
            $scenario = optional($first->decision)->scenario;

            $label = $scenario ? $scenario->title : ('Scenario #' . $scenarioId);
            $avg   = $results->avg('score');
            $count = $results->count();

            $scenarioLabels->push($label);
            $scenarioAvgScores->push(round($avg, 1));
            $scenarioGradeCount->push($count);
        }

        // 供旧页面使用的“三个卡片”数据（用更直观的名字）
        $overallAverage        = $averageScore ? round($averageScore, 1) : null;
        $scenariosWithGrades   = $groupedByScenario->filter()->count();
        $totalGradedDecisions  = $allGradedResults->count();

        return view('admin.dashboard', [
            // 原有 summary
            'totalScenarios'        => $totalScenarios,
            'totalUsers'            => $totalUsers,
            'totalDecisions'        => $totalDecisions,
            'averageScore'          => $averageScore,
            'pendingDecisionsCount' => $pendingDecisionsCount,
            'recentDecisions'       => $recentDecisions,
            'ungradedDecisions'     => $ungradedDecisions,
            'chartLabels'           => $chartLabels,
            'chartData'             => $chartData,

            // 新增：用于 Score Analytics 柱状图
            'overallAverage'        => $overallAverage,
            'scenariosWithGrades'   => $scenariosWithGrades,
            'totalGradedDecisions'  => $totalGradedDecisions,
            'scenarioLabels'        => $scenarioLabels,
            'scenarioAvgScores'     => $scenarioAvgScores,
            'scenarioGradeCount'    => $scenarioGradeCount,
        ]);
    }
}
