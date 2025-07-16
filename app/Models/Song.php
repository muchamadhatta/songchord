<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Song extends Model
{
    protected $guarded = [];

    /** artist yang membawakan lagu */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    // relasi lain (opsional)
    public function versions()
    {
        return $this->hasMany(SongVersion::class);
    }

    public function defaultVersion()
    {
        return $this->hasOne(SongVersion::class)->where('is_default', true);
    }
}
