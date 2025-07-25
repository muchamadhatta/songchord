<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'artist' => new ArtistResource($this->whenLoaded('artist')),
            'title' => $this->title,
            'original_key' => $this->original_key,
            'bpm' => $this->bpm,
            'time_signature' => $this->time_signature,
            'capo' => $this->capo,
            'default_version' => new VersionResource($this->whenLoaded('defaultVersion')),
        ];
    }
}
