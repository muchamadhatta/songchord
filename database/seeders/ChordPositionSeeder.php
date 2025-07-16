<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ChordPositionSeeder extends Seeder
{
    public function run(): void
    {
        // ambil ID baris
        $lines = DB::table('lines')->pluck('id', 'lyrics_text'); // [text => id]

        $data = [
            // baris 1 VERSE: "Kumencoba, untuk terus bertahan"
            ['lyrics' => 'Kumencoba, untuk terus bertahan', 'chords' => [
                ['idx' => 0,  'chord' => 'C'],
                ['idx' => 10, 'chord' => 'G/B'],
                ['idx' => 17, 'chord' => 'Am'],
            ]],
            // baris 2 VERSE
            ['lyrics' => 'Menahan rasa yang selalu datang', 'chords' => [
                ['idx' => 0,  'chord' => 'F'],
                ['idx' => 9,  'chord' => 'G'],
            ]],
            // baris 1 CHORUS: "Aku kangen kamu"
            ['lyrics' => 'Aku kangen kamu', 'chords' => [
                ['idx' => 0, 'chord' => 'C'],
                ['idx' => 4, 'chord' => 'G'],
                ['idx' => 11,'chord' => 'Am'],
            ]],
            // baris 2 CHORUS
            ['lyrics' => 'Tak akan pernah terganti', 'chords' => [
                ['idx' => 0, 'chord' => 'F'],
                ['idx' => 15,'chord' => 'G'],
            ]],
        ];

        $now = now();
        foreach ($data as $row) {
            $lineId = $lines[$row['lyrics']];
            foreach ($row['chords'] as $order => $c) {
                DB::table('chord_positions')->insert([
                    'line_id'       => $lineId,
                    'char_index'    => $c['idx'],
                    'chord'         => $c['chord'],
                    'display_order' => $order,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }
    }
}
