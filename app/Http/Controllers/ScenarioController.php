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

    /**
     * Convert any YouTube URL to embed format
     */
    private function convertYoutubeUrl($url)
    {
        if (!$url) return null;

        // full link e.g. https://www.youtube.com/watch?v=abcd
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/" . $m[1];
        }

        // short link e.g. https://youtu.be/abcd
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/" . $m[1];
        }

        // already embed
        if (preg_match('/youtube\.com\/embed\/([^?]+)/', $url)) {
            return $url;
        }

        return $url;
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

            'status'      => 'required|in:open,closed',
            'deadline'    => 'nullable|date',

            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:20480',

            // ⭐ Video (max 2MB)
            'video'       => 'nullable|file|mimes:mp4,webm,ogg|max:2048',
            'video_url'   => 'nullable|url',
        ]);

        $validated['created_by'] = auth()->id();

        // Image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('scenario_images', 'public');
        }

        // Attachment upload
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('scenario_files', 'public');
        }

        // ⭐ Video file upload
        if ($request->hasFile('video')) {
            $validated['video_path'] = $request->file('video')->store('scenario_videos', 'public');
        }

        // ⭐ YouTube URL convert
        if ($request->filled('video_url')) {
            $validated['video_url'] = $this->convertYoutubeUrl($request->video_url);
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

            'status'      => 'required|in:open,closed',
            'deadline'    => 'nullable|date',

            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:20480',

            'video'       => 'nullable|file|mimes:mp4,webm,ogg|max:2048',
            'video_url'   => 'nullable|url',
        ]);

        // Image replace
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('scenario_images', 'public');
        }

        // File replace
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('scenario_files', 'public');
        }

        // Video replace
        if ($request->hasFile('video')) {
            $validated['video_path'] = $request->file('video')->store('scenario_videos', 'public');
        }

        // YouTube URL convert
        if ($request->filled('video_url')) {
            $validated['video_url'] = $this->convertYoutubeUrl($request->video_url);
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
