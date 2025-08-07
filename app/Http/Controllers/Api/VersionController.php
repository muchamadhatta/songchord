<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVersionRequest;
use App\Http\Resources\VersionResource;
use App\Models\Song;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function store(StoreVersionRequest $req, Song $song)
    {
        $data = $req->validated();
        $data['created_by'] = $req->user()->id;

        $songVersion = $song->versions()->create($data);

        return new VersionResource($songVersion->load('song'));
    }
}

