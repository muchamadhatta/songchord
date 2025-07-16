<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongVersion extends Model
{
    protected $guarded = [];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function sections()
    {
        return $this->hasMany(SongSection::class);
    }
}
