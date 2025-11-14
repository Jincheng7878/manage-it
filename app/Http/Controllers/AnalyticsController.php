<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Result;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function scoreOverview()
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Only admin users can view analytics.');
        }

        // -----------------------------
        //  Top summary cards
        // -----------------------------
        $totalScenarios = Scenario::count();
        $totalUsers = User::count();
        $totalDecisions = Decision::count();
        $averageScore = Result::avg('score');

        // Pending decisions
        $pendingDecisionsCount = Result::whereNull('score')->count();

        // -----------------------------
        //  Line chart - last 7 days
        // -----------------------------
        $chartDataRaw = Decision::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = $day;

            $found = $chartDataRaw->firstWhere('date', $day);
            $chartData[] = $found ? $found->total : 0;
        }

        // -----------------------------
        // Recent decisions (Eloquent!)
        // -----------------------------
        $recentDecisions = Decision::with(['user', 'scenario', 'result'])
            ->latest()
            ->limit(10)
            ->get();

        // -----------------------------
        // Ungraded decisions
        // -----------------------------
        $ungradedDecisions = Decision::with(['user', 'scenario'])
            ->whereDoesntHave('result')
            ->latest()
            ->limit(10)
            ->get();

        // -----------------------------
        // Scenario score analytics (bar chart)
        // -----------------------------
        $scenarioStats = Result::query()
            ->join('decisions', 'results.decision_id', '=', 'decisions.id')
            ->join('scenarios', 'decisions.scenario_id', '=', 'scenarios.id')
            ->select(
                'scenarios.id',
                'scenarios.title',
                DB::raw('AVG(results.score) as avg_score'),
                DB::raw('COUNT(results.id) as graded_count')
            )
            ->groupBy('scenarios.id', 'scenarios.title')
            ->orderBy('scenarios.title')
            ->get();

        return view('admin.dashboard', [
            'totalScenarios'       => $totalScenarios,
            'totalUsers'           => $totalUsers,
            'totalDecisions'       => $totalDecisions,
            'averageScore'         => $averageScore,
            'pendingDecisionsCount'=> $pendingDecisionsCount,

            'chartLabels'          => $chartLabels,
            'chartData'            => $chartData,

            'recentDecisions'      => $recentDecisions,
            'ungradedDecisions'    => $ungradedDecisions,

            'scenarioLabels'       => $scenarioStats->pluck('title'),
            'scenarioAvgScores'    => $scenarioStats->pluck('avg_score'),
            'scenarioGradeCount'   => $scenarioStats->pluck('graded_count'),

            'overallAverage'       => round(Result::avg('score') ?? 0, 1),
            'scenariosWithGrades'  => $scenarioStats->count(),
            'totalGradedDecisions' => Result::count(),
        ]);
    }
}
