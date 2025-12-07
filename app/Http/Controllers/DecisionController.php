<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Scenario;
use Illuminate\Http\Request;

class DecisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List decisions under a scenario
    public function index(Scenario $scenario)
    {
        $decisions = Decision::where('scenario_id', $scenario->id)
            ->with('user', 'result')
            ->paginate(10);

        return view('decisions.index', compact('scenario', 'decisions'));
    }

    // Show create decision form
    public function create(Scenario $scenario)
    {
        if (!$scenario->isOpenForSubmission()) {
            return redirect()
                ->route('scenarios.show', $scenario)
                ->with('error', 'This scenario is closed for new submissions.');
        }

        return view('decisions.create', compact('scenario'));
    }

    // Store student's decision + ⭐ XP 奖励
    public function store(Request $request, Scenario $scenario)
    {
        if (!$scenario->isOpenForSubmission()) {
            return redirect()
                ->route('scenarios.show', $scenario)
                ->with('error', 'This scenario is closed for new submissions.');
        }

        $validated = $request->validate([
            'strategy'       => 'required|string|max:2000',
            'time_alloc'     => 'required|integer|min:0',
            'cost_alloc'     => 'required|integer|min:0',
            'risk_level'     => 'required|in:low,medium,high',
            'notes'          => 'nullable|string',

            // structured decision fields
            'swot_strengths'     => 'nullable|string',
            'swot_weaknesses'    => 'nullable|string',
            'swot_opportunities' => 'nullable|string',
            'swot_threats'       => 'nullable|string',
            'wbs'                => 'nullable|string',
            'risk_matrix'        => 'nullable|string',
            'cost_breakdown'     => 'nullable|string',

            // optional attachment
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls,ppt,pptx|max:20480',
        ]);

        $validated['scenario_id'] = $scenario->id;
        $validated['user_id']     = auth()->id();

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('decision_files', 'public');
        }

        // 创建决策
        $decision = Decision::create($validated);

        /* ==================================================
         *  ⭐ NEW：提交成功后给学生增加 XP
         *  规则：根据 scenario 的 difficulty 不同给不同 XP
         *  easy   → 10 XP
         *  medium → 20 XP
         *  hard   → 30 XP
         * ================================================== */
        $user = auth()->user();

        $xpGain = match ($scenario->difficulty) {
            'easy'   => 10,
            'medium' => 20,
            'hard'   => 30,
            default  => 15, // 如果将来多了新难度，给个默认值
        };

        // 增加经验值（users 表里已经有 xp 字段）
        $user->increment('xp', $xpGain);

        return redirect()
            ->route('scenarios.show', $scenario)
            ->with('success', "Decision submitted. You gained {$xpGain} XP. Please wait for teacher grading.");
    }

    // Delete decision (owner or admin)
    public function destroy(Decision $decision)
    {
        $user = auth()->user();

        if (!$user || ($user->id !== $decision->user_id && $user->role !== 'admin')) {
            abort(403, 'You are not allowed to delete this decision.');
        }

        $scenario = $decision->scenario;

        $decision->delete();

        return redirect()
            ->route('scenarios.show', $scenario)
            ->with('success', 'Decision deleted.');
    }
}
