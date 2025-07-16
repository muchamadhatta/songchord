<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lyrics_text' => $this->lyrics_text,
            'chord_positions' => ChordPositionResource::collection($this->whenLoaded('chordPositions')),
        ];
    }
}
