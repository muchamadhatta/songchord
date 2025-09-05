<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{

    public function index(Request $request)
    {
        $query = Song::with(['artist', 'creator']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('artist', function($artistQuery) use ($search) {
                      $artistQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by key
        if ($request->has('key') && $request->key) {
            $query->where('original_key', $request->key);
        }

        // Filter by artist
        if ($request->has('artist') && $request->artist) {
            $query->where('artist_id', $request->artist);
        }

        $songs = $query->latest()->paginate(15);
        $artists = Artist::orderBy('name')->get();
        
        // Get unique keys for filter
        $keys = Song::whereNotNull('original_key')
                   ->distinct()
                   ->pluck('original_key')
                   ->sort()
                   ->values();

        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs']
        ];

        return view('admin.songs.index', compact('songs', 'artists', 'keys', 'breadcrumbs'));
    }

    public function create()
    {
        $artists = Artist::orderBy('name')->get();
        
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs', 'url' => route('songs.index')],
            ['title' => 'Create Song']
        ];

        return view('admin.songs.create', compact('artists', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,id',
            'original_key' => 'nullable|string|max:3',
            'bpm' => 'nullable|integer|min:1|max:300',
            'time_signature' => 'nullable|string|max:8',
            'capo' => 'nullable|integer|min:0|max:12',
            'youtube_url' => 'nullable|url|max:255',
        ]);

        $validated['created_by'] = Auth::id();

        $song = Song::create($validated);

        return redirect()->route('songs.index')
                        ->with('success', 'Song created successfully!');
    }

    public function show(Song $song)
    {
        $song->load(['artist', 'creator', 'versions.sections.lines.chordPositions']);
        
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs', 'url' => route('songs.index')],
            ['title' => $song->title]
        ];

        return view('admin.songs.show', compact('song', 'breadcrumbs'));
    }

    public function edit(Song $song)
    {
        $artists = Artist::orderBy('name')->get();
        
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs', 'url' => route('songs.index')],
            ['title' => $song->title, 'url' => route('songs.show', $song)],
            ['title' => 'Edit']
        ];

        return view('admin.songs.edit', compact('song', 'artists', 'breadcrumbs'));
    }

    public function update(Request $request, Song $song)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,id',
            'original_key' => 'nullable|string|max:3',
            'bpm' => 'nullable|integer|min:1|max:300',
            'time_signature' => 'nullable|string|max:8',
            'capo' => 'nullable|integer|min:0|max:12',
            'youtube_url' => 'nullable|url|max:255',
        ]);

        $song->update($validated);

        return redirect()->route('songs.index')
                        ->with('success', 'Song updated successfully!');
    }

    public function destroy(Song $song)
    {
        $song->delete();

        return redirect()->route('songs.index')
                        ->with('success', 'Song deleted successfully!');
    }
}