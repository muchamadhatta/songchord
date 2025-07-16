<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        $artistId = DB::table('artists')->where('name', 'Dewa 19')->value('id');
        $adminId  = DB::table('users')->where('username', 'admin')->value('id');

        DB::table('songs')->insert([
            'artist_id'     => $artistId,
            'title'         => 'Kangen',
            'original_key'  => 'C',
            'bpm'           => 66,
            'time_signature'=> '4/4',
            'capo'          => null,
            'youtube_url'   => 'https://youtu.be/Jwc25h-Hh7I',
            'created_by'    => $adminId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
