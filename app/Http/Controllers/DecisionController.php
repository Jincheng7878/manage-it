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

    // List decisions for a specific scenario
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
        return view('decisions.create', compact('scenario'));
    }

    // Store student's decision (no auto-scoring; teacher will grade)
    public function store(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([
            'strategy'   => 'required|string|max:1000',
            'time_alloc' => 'required|integer|min:0',
            'cost_alloc' => 'required|integer|min:0',
            'risk_level' => 'required|in:low,medium,high',
            'notes'      => 'nullable|string',
            // file upload from student
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,ppt,pptx|max:20480',
        ]);

        $validated['scenario_id'] = $scenario->id;
        $validated['user_id']     = auth()->id();

        // handle file upload
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('decision_files', 'public');
        }

        Decision::create($validated);

        // Teacher will grade later manually
        return redirect()
            ->route('scenarios.show', $scenario)
            ->with('success', 'Decision submitted. Please wait for teacher grading.');
    }

    // NEW: delete a decision
    public function destroy(Decision $decision)
    {
        $user = auth()->user();

        // Only the owner of the decision OR an admin can delete
        if ($user->id !== $decision->user_id && $user->role !== 'admin') {
            abort(403, 'You are not allowed to delete this decision.');
        }

        $scenario = $decision->scenario; // used for redirect

        $decision->delete();

        return redirect()
            ->route('decisions.index', $scenario)
            ->with('success', 'Decision deleted successfully.');
    }

    // other resource methods (show, edit, update) can be added if needed
}
