<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChordPosition extends Model
{
    protected $guarded = [];

    public function line()
    {
        return $this->belongsTo(SongLine::class, 'song_line_id');
    }
}
