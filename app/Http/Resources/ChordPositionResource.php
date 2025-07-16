<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChordPositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'char_index' => $this->char_index,
            'chord' => $this->chord,
            'display_order' => $this->display_order,
        ];
    }
}
