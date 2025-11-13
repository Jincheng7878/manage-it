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

            // new fields
            'status'      => 'required|in:open,closed',
            'deadline'    => 'nullable|date',

            // teacher uploads
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:20480',
        ]);

        $validated['created_by'] = auth()->id();

        // handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('scenario_images', 'public');
        }

        // handle attachment upload
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('scenario_files', 'public');
        }

        Scenario::create($validated);

        return redirect()
            ->route('scenarios.index')
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

            // new fields
            'status'      => 'required|in:open,closed',
            'deadline'    => 'nullable|date',

            // teacher uploads
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:20480',
        ]);

        // image overwrite (if new uploaded)
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('scenario_images', 'public');
        }

        // file overwrite
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('scenario_files', 'public');
        }

        $scenario->update($validated);

        return redirect()
            ->route('scenarios.show', $scenario)
            ->with('success', 'Scenario updated.');
    }

    public function destroy(Scenario $scenario)
    {
        $this->authorize('delete', $scenario);

        $scenario->delete();

        return redirect()
            ->route('scenarios.index')
            ->with('success', 'Scenario deleted.');
    }
}
