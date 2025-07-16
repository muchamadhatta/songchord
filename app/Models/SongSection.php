<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongSection extends Model
{
    protected $guarded = [];

    public function version()
    {
        return $this->belongsTo(SongVersion::class, 'song_version_id');
    }

    public function lines()
    {
        return $this->hasMany(SongLine::class);
    }
}
