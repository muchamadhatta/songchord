<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'version_label' => $this->version_label,
            'is_default'    => $this->is_default,
            'song_id'       => $this->song_id,

            'song'     => new SongResource($this->whenLoaded('song')),
            'sections' => SectionResource::collection($this->whenLoaded('sections')),
        ];
    }
}
