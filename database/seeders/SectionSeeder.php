<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $versionId = DB::table('song_versions')->where('version_label', 'Original')->value('id');

        DB::table('sections')->insert([
            [
                'song_version_id' => $versionId,
                'section_type'    => 'VERSE',
                'section_order'   => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'song_version_id' => $versionId,
                'section_type'    => 'CHORUS',
                'section_order'   => 2,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
