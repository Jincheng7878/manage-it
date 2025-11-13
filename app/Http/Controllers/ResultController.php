<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Scenario;
use App\Models\Decision;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 老师查看所有结果 / 学生查看自己的结果
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $results = Result::with(['decision.user', 'decision.scenario'])
                ->latest()
                ->paginate(10);
        } else {
            $results = Result::whereHas('decision', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with(['decision.user', 'decision.scenario'])
                ->latest()
                ->paginate(10);
        }

        return view('results.index', compact('results'));
    }

    /**
     * 结果详情页面
     */
    public function show(Result $result)
    {
        $this->authorize('view', $result);

        $result->load('decision.user', 'decision.scenario');

        return view('results.show', compact('result'));
    }

    /**
     * ================
     * 🔥 老师评分页面
     * ================
     */
    public function gradeList(Scenario $scenario)
    {
        // 只有 admin 才能进入评分
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admin can grade.');
        }

        // 获取学生提交的所有决策
        $scenario->load('decisions.user', 'decisions.result');

        return view('results.grade', compact('scenario'));
    }

    /**
     * ================
     * 🔥 老师提交评分
     * ================
     */
    public function grade(Request $request, Decision $decision)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admin can submit grades.');
        }

        $validated = $request->validate([
            'score'    => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000'
        ]);

        // 如果该决策还没有 result，则创建一个
        $result = $decision->result ?? new Result();
        $result->decision_id = $decision->id;
        $result->score       = $validated['score'];
        $result->feedback    = $validated['feedback'];
        $result->save();

        return back()->with('success', '评分已保存。');
    }

    // 不允许外部创建或删除 result
    public function create()  { abort(404); }
    public function store(Request $r) { abort(404); }
    public function edit(Result $r) { abort(404); }
    public function update(Request $r, Result $rr) { abort(404); }
    public function destroy(Result $r) { abort(404); }
}
