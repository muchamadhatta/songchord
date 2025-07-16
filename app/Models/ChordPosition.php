<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChordPosition extends Model
{
    protected $table = 'chord_positions';

    protected $guarded = [];

    public function line()
    {
        return $this->belongsTo(SongLine::class, 'line_id');
    }
}
