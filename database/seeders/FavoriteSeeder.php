<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('username', 'admin')->value('id');
        $songId = DB::table('songs')->where('title', 'Kangen')->value('id');

        DB::table('favorites')->insert([
            'user_id'    => $userId,
            'song_id'    => $songId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
