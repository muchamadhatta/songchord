<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSongRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Set true agar request diizinkan
        return true;
    }

    public function rules(): array
    {
        return [
            'artist_id'       => 'required|exists:artists,id',
            'title'           => 'required|string|max:255',
            'original_key'    => 'nullable|string|max:3',
            'bpm'             => 'nullable|integer',
            'time_signature'  => 'nullable|string|max:8',
            'capo'            => 'nullable|integer',
            'youtube_url'     => 'nullable|url',
        ];
    }
}
