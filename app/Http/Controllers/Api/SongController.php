<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Http\Resources\SongResource;
use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(Request $req)
    {
        $songs = Song::with('artist')
            ->when($req->search, fn($q) =>
                $q->where('title', 'like', "%{$req->search}%"))
            ->when($req->artist_id, fn($q) =>
                $q->where('artist_id', $req->artist_id))
            ->paginate($req->per_page ?? 15);

        return SongResource::collection($songs);
    }

    public function show(Song $song)
    {
        $song->load([
            'artist',
            'defaultVersion.sections.lines.chordPositions'
        ]);

        return new SongResource($song);
    }

    public function store(StoreSongRequest $req)
    {
        $song = Song::create($req->validated() + ['created_by' => $req->user()->id]);

        return new SongResource($song->load('artist'));
    }
    

    public function update(UpdateSongRequest $req, Song $song)
    {
        // $this->authorize('update', $song);
        $song->update($req->validated());

        return new SongResource($song->refresh()->load('artist'));
    }

    public function destroy(Song $song)
    {
        // $this->authorize('delete', $song);
        $song->delete();

        return response()->noContent();
    }
}
