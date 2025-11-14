@php
    use Illuminate\Support\Str;

    /**
     * Extract a usable YouTube embed URL from any YouTube link format.
     */
    function getYoutubeEmbedUrl($url) {
        if (!$url) return null;

        if (preg_match('/v=([^&]+)/', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/" . $m[1];
        }
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/" . $m[1];
        }
        if (preg_match('/embed\/([^?]+)/', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/" . $m[1];
        }
        if (preg_match('/shorts\/([^?]+)/', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/" . $m[1];
        }

        return null;
    }

    $embedUrl = getYoutubeEmbedUrl($scenario->video_url);
@endphp

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            Scenario Details - {{ $scenario->title }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
                @endif

                <p class="text-xs text-gray-500 mb-3">
                    Logged in as: <strong>{{ Auth::user()->role }}</strong>
                </p>

                {{-- IMAGE --}}
                @if($scenario->image_path)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $scenario->image_path) }}"
                             class="w-full max-h-80 object-cover rounded-lg shadow-md border"
                             alt="Scenario image">
                    </div>
                @endif

                {{-- VIDEO --}}
                @if($scenario->video_path || $embedUrl)
                    <h3 class="text-lg font-semibold mb-2">Scenario Video</h3>

                    @if($scenario->video_path)
                        <video controls width="100%"
                               class="rounded-lg shadow-md mb-6">
                            <source src="{{ asset('storage/' . $scenario->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif

                    @if($embedUrl)
                        <div class="mb-6">
                            <iframe width="100%"
                                    height="380"
                                    class="rounded-lg shadow-md border"
                                    src="{{ $embedUrl }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                            </iframe>
                        </div>
                    @endif
                @endif

                {{-- SCENARIO INFO --}}
                <div class="flex justify-between items-start mb-6">

                    <div class="pr-4">
                        <h1 class="text-3xl font-extrabold text-gray-900">{{ $scenario->title }}</h1>

                        <p class="text-gray-700 mt-3 leading-relaxed">
                            {{ $scenario->description }}
                        </p>

                        <div class="text-sm text-gray-600 mt-4 space-y-1">
                            <p><strong>Budget:</strong> £{{ $scenario->budget }}</p>
                            <p><strong>Duration:</strong> {{ $scenario->duration }} days</p>
                            <p><strong>Difficulty:</strong> {{ ucfirst($scenario->difficulty) }}</p>

                            <p>
                                <strong>Status:</strong>
                                @if($scenario->isOpenForSubmission())
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs font-semibold">Open</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-semibold">Closed</span>
                                @endif
                            </p>

                            <p>
                                <strong>Deadline:</strong>
                                @if($scenario->deadline)
                                    {{ $scenario->deadline->format('Y-m-d H:i') }}
                                    @if(now()->greaterThan($scenario->deadline))
                                        <span class="text-xs text-red-600">(passed)</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-500">No deadline</span>
                                @endif
                            </p>
                        </div>

                        @if($scenario->file_path)
                            <p class="text-sm mt-4">
                                <strong>Attachment:</strong>
                                <a href="{{ asset('storage/' . $scenario->file_path) }}"
                                   target="_blank"
                                   class="text-blue-600 underline">
                                    Download File
                                </a>
                            </p>
                        @endif
                    </div>

                    {{-- BUTTONS --}}
                    <div class="text-right space-y-3">

                        @if($scenario->isOpenForSubmission())
                            <a href="{{ route('decisions.create', $scenario) }}"
                               class="block px-4 py-2 text-center font-semibold text-black
                                      bg-gradient-to-r from-green-300 to-green-100
                                      rounded-xl shadow-md border border-green-400 hover:shadow-lg">
                                Submit Decision
                            </a>
                        @else
                            <div class="px-4 py-2 text-xs text-center bg-gray-100 border-gray-300 rounded-xl">
                                Submissions closed.
                            </div>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('results.gradeList', $scenario) }}"
                               class="block px-4 py-2 text-center font-semibold text-black
                                      bg-gradient-to-r from-purple-300 to-pink-200
                                      rounded-xl shadow-md border border-purple-400">
                                ⭐ Grade Decisions ⭐
                            </a>
                        @endif
                    </div>

                </div>

                {{-- DECISIONS LIST --}}
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Submitted Decisions</h2>

                @if($scenario->decisions->isEmpty())
                    <p class="text-sm text-gray-500">No decisions submitted yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($scenario->decisions as $d)
                            <div class="p-4 bg-gray-50 border rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex justify-between">
                                    <div class="pr-4">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $d->user->name }}
                                        </div>

                                        <div class="text-sm text-gray-700 mt-1">
                                            {{ Str::limit($d->strategy, 200) }}
                                        </div>

                                        @if($d->file_path)
                                            <p class="text-xs mt-2">
                                                <a href="{{ asset('storage/' . $d->file_path) }}"
                                                   target="_blank"
                                                   class="text-blue-600 underline">
                                                    Download Student File
                                                </a>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="text-right text-sm flex flex-col items-end space-y-2">
                                        <div>
                                            <strong>Score:</strong>
                                            <span class="font-bold text-indigo-700">
                                                {{ optional($d->result)->score ?? '—' }}
                                            </span>
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $d->created_at->diffForHumans() }}
                                        </div>

                                        @if(Auth::id() === $d->user_id || Auth::user()->role === 'admin')
                                            <form method="POST"
                                                  action="{{ route('decisions.destroy', $d) }}"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif


                {{-- ====================================== --}}
                {{--            COMMENTS AREA               --}}
                {{-- ====================================== --}}
                <h2 class="text-xl font-semibold mt-10 mb-4 text-gray-900">Discussion & Comments</h2>

                {{-- New Comment Form --}}
                <div class="bg-white border rounded-xl p-4 shadow-sm mb-6">
                    <form method="POST" action="{{ route('comments.store', $scenario) }}">
                        @csrf
                        <textarea name="content"
                                  class="w-full border rounded p-2"
                                  placeholder="Write a comment..."
                                  required></textarea>

                        <button class="mt-2 px-4 py-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                            Post Comment
                        </button>
                    </form>
                </div>

                {{-- Comments List --}}
                @if($scenario->comments->isEmpty())
                    <p class="text-sm text-gray-500">No comments yet. Be the first to comment!</p>
                @else
                    <div class="space-y-3">
                        @foreach($scenario->comments as $comment)
                            <div class="p-4 bg-gray-50 border rounded-xl shadow-sm">

                                {{-- User + date --}}
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <span class="font-semibold text-gray-900">
                                            {{ $comment->user->name }}
                                        </span>
                                        <span class="text-xs text-gray-500 ml-2">
                                            ({{ $comment->user->role }})
                                        </span>
                                    </div>

                                    <span class="text-xs text-gray-400">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                {{-- Content --}}
                                <p class="text-gray-700">{{ $comment->content }}</p>

                                {{-- Delete --}}
                                @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                                    <form action="{{ route('comments.destroy', $comment) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this comment?');"
                                          class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 text-xs hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
