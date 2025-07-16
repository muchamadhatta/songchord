<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongLine extends Model
{
    protected $table = 'lines';

    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(SongSection::class, 'section_id');
    }
    

    public function chordPositions()
    {
        return $this->hasMany(ChordPosition::class, 'line_id');
    }

}

