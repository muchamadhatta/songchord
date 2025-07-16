<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SongVersionSeeder extends Seeder
{
    public function run(): void
    {
        $songId  = DB::table('songs')->where('title', 'Kangen')->value('id');
        $adminId = DB::table('users')->where('username', 'admin')->value('id');

        DB::table('song_versions')->insert([
            'song_id'      => $songId,
            'version_label'=> 'Original',
            'is_default'   => true,
            'created_by'   => $adminId,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
