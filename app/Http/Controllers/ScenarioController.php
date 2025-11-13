<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $scenarios = Scenario::latest()->paginate(9);
        return view('scenarios.index', compact('scenarios'));
    }

    public function create()
    {
        // Use Policy to check if user can create
        $this->authorize('create', Scenario::class);

        return view('scenarios.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Scenario::class);

        $validated = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable|string',
            'budget'      => 'required|integer|min:0',
            'duration'    => 'required|integer|min:1',
            'difficulty'  => 'required|in:easy,medium,hard',
            // file upload (teacher can attach scenario file)
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,ppt,pptx|max:20480',
        ]);

        // handle file upload
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('scenario_files', 'public');
        }

        $validated['created_by'] = auth()->id();

        Scenario::create($validated);

        return redirect()->route('scenarios.index')
            ->with('success', 'Scenario created successfully.');
    }

    public function show(Scenario $scenario)
    {
        $scenario->load([
            'decisions.user',
            'decisions.result',
        ]);

        return view('scenarios.show', compact('scenario'));
    }

    public function edit(Scenario $scenario)
    {
        // Use ScenarioPolicy@update
        $this->authorize('update', $scenario);

        return view('scenarios.edit', compact('scenario'));
    }

    public function update(Request $request, Scenario $scenario)
    {
        $this->authorize('update', $scenario);

        $validated = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable|string',
            'budget'      => 'required|integer|min:0',
            'duration'    => 'required|integer|min:1',
            'difficulty'  => 'required|in:easy,medium,hard',
            // optional new file
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,ppt,pptx|max:20480',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('scenario_files', 'public');
        }

        $scenario->update($validated);

        return redirect()->route('scenarios.show', $scenario)
            ->with('success', 'Scenario updated.');
    }

    public function destroy(Scenario $scenario)
    {
        // Use ScenarioPolicy@delete
        $this->authorize('delete', $scenario);

        $scenario->delete();

        return redirect()->route('scenarios.index')
            ->with('success', 'Scenario deleted.');
    }
}
