<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class EditHistorySeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->where('username', 'admin')->value('id');
        $songId  = DB::table('songs')->where('title', 'Kangen')->value('id');

        DB::table('edit_history')->insert([
            'entity_type' => 'SONG',
            'entity_id'   => $songId,
            'prev_json'   => json_encode(['title' => 'Kangen (Draft)']),
            'new_json'    => json_encode(['title' => 'Kangen']),
            'edited_by'   => $adminId,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
