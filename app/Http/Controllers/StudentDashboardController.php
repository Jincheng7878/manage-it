<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\Decision;
use App\Models\Result;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // ---------- My decisions summary ----------
        $totalMyDecisions = Decision::where('user_id', $user->id)->count();

        // My average score
        $avgMyScore = Result::whereHas('decision', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->avg('score');

        // ---------- My latest decisions ----------
        $myRecentDecisions = Decision::with(['scenario', 'result'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // ---------- My scenarios (where I have submitted decisions) ----------
        $myScenarioIds = Decision::where('user_id', $user->id)
            ->pluck('scenario_id')
            ->unique()
            ->toArray();

        $myScenarios = Scenario::whereIn('id', $myScenarioIds)
            ->latest()
            ->take(5)
            ->get();

        // ---------- Pending scenarios (scenarios I have NOT submitted to) ----------
        $pendingScenarios = Scenario::when(!empty($myScenarioIds), function ($q) use ($myScenarioIds) {
                $q->whereNotIn('id', $myScenarioIds);
            })
            ->latest()
            ->take(5)
            ->get();

        $pendingCount = $pendingScenarios->count();

        // ---------- My files (decisions with attachments) ----------
        $myFiles = Decision::with('scenario')
            ->where('user_id', $user->id)
            ->whereNotNull('file_path')
            ->latest()
            ->take(5)
            ->get();

        $filesCount = Decision::where('user_id', $user->id)
            ->whereNotNull('file_path')
            ->count();

        // ---------- Chart data: my scores over time ----------
        $myResults = Result::with('decision.scenario')
            ->whereHas('decision', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(10)
            ->get()
            ->reverse(); // so chart goes oldest -> newest

        $scoreLabels = [];
        $scoreData   = [];

        foreach ($myResults as $result) {
            $scenario = optional($result->decision)->scenario;
            $label = $scenario ? $scenario->title : 'Scenario #' . optional($result->decision)->scenario_id;
            // 可以带日期： "Scenario title (dd/mm)"
            $label .= ' (' . $result->created_at->format('d/m') . ')';

            $scoreLabels[] = $label;
            $scoreData[]   = $result->score;
        }

        return view('student.dashboard', [
            'user'             => $user,
            'totalMyDecisions' => $totalMyDecisions,
            'avgMyScore'       => $avgMyScore,
            'myRecentDecisions'=> $myRecentDecisions,
            'myScenarios'      => $myScenarios,
            'pendingScenarios' => $pendingScenarios,
            'pendingCount'     => $pendingCount,
            'myFiles'          => $myFiles,
            'filesCount'       => $filesCount,
            'scoreLabels'      => $scoreLabels,
            'scoreData'        => $scoreData,
        ]);
    }
}
