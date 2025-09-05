<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artist extends Model
{
    use HasFactory;

    protected $table = 'artists';

    protected $guarded = [];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
