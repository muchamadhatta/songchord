<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class LineSeeder extends Seeder
{
    public function run(): void
    {
        $verseId  = DB::table('sections')->where('section_type', 'VERSE')->value('id');
        $chorusId = DB::table('sections')->where('section_type', 'CHORUS')->value('id');

        DB::table('lines')->insert([
            // VERSE
            [
                'section_id'  => $verseId,
                'line_order'  => 1,
                'lyrics_text' => 'Kumencoba, untuk terus bertahan',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'section_id'  => $verseId,
                'line_order'  => 2,
                'lyrics_text' => 'Menahan rasa yang selalu datang',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // CHORUS
            [
                'section_id'  => $chorusId,
                'line_order'  => 1,
                'lyrics_text' => 'Aku kangen kamu',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'section_id'  => $chorusId,
                'line_order'  => 2,
                'lyrics_text' => 'Tak akan pernah terganti',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
