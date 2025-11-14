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
     * Teacher: see all results
     * Student: see own results (grades)
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
     * Student: "My Decisions" page
     * (list all decisions created by this user)
     */
    public function myDecisions()
    {
        $user = auth()->user();

        $decisions = Decision::where('user_id', $user->id)
            ->with(['scenario', 'result'])
            ->latest()
            ->paginate(10);

        return view('results.my_decisions', compact('decisions'));
    }

    /**
     * Result detail page
     */
    public function show(Result $result)
    {
        $this->authorize('view', $result);

        $result->load('decision.user', 'decision.scenario');

        return view('results.show', compact('result'));
    }

    /**
     * Teacher: grade page for one scenario
     */
    public function gradeList(Scenario $scenario)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admin can grade.');
        }

        $scenario->load('decisions.user', 'decisions.result');

        return view('results.grade', compact('scenario'));
    }

    /**
     * Teacher: save grade for one decision
     */
    public function grade(Request $request, Decision $decision)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admin can submit grades.');
        }

        $validated = $request->validate([
            'score'    => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $result = $decision->result ?? new Result();
        $result->decision_id = $decision->id;
        $result->score       = $validated['score'];
        $result->feedback    = $validated['feedback'];
        $result->save();

        return back()->with('success', 'Grade saved successfully.');
    }

    // Disable unused actions
    public function create()  { abort(404); }
    public function store(Request $r) { abort(404); }
    public function edit(Result $r) { abort(404); }
    public function update(Request $r, Result $rr) { abort(404); }
    public function destroy(Result $r) { abort(404); }
}
