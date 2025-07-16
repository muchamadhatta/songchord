<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('artists')->insert([
            'name'        => 'Dewa 19',
            'country'     => 'Indonesia',
            'formed_year' => 1986,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
