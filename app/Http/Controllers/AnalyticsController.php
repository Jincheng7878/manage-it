<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Score overview page for teachers (admin only).
     */
    public function scoreOverview()
    {
        $user = auth()->user();

        // Only admin / teacher can see analytics
        if ($user->role !== 'admin') {
            abort(403, 'Only admin users can view analytics.');
        }

        // Average score per scenario
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
            ->orderByDesc('avg_score')
            ->get();

        $scenarioLabels     = $scenarioStats->pluck('title');
        $scenarioAvgScores  = $scenarioStats->pluck('avg_score')->map(fn($v) => round($v, 1));
        $scenarioGradeCount = $scenarioStats->pluck('graded_count');

        // Global stats
        $overallAverage = round(Result::avg('score') ?? 0, 1);
        $totalDecisions = Result::count();
        $totalScenarios = $scenarioStats->count();

        return view('analytics.scores', [
            'scenarioLabels'     => $scenarioLabels,
            'scenarioAvgScores'  => $scenarioAvgScores,
            'scenarioGradeCount' => $scenarioGradeCount,
            'overallAverage'     => $overallAverage,
            'totalDecisions'     => $totalDecisions,
            'totalScenarios'     => $totalScenarios,
        ]);
    }
}
