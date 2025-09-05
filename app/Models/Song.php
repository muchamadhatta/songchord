<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Song extends Model
{
    use HasFactory;

    protected $table = 'songs';

    protected $guarded = [];

    /** artist yang membawakan lagu */
public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function versions()
    {
        return $this->hasMany(SongVersion::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function defaultVersion()
    {
        return $this->hasOne(SongVersion::class)->where('is_default', true);
    }
}
