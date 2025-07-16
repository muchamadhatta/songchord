<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // urutan penting karena FK
        $this->call([
            ArtistSeeder::class,
            UserSeeder::class,
            SongSeeder::class,
            SongVersionSeeder::class,
            SectionSeeder::class,
            LineSeeder::class,
            ChordPositionSeeder::class,
            FavoriteSeeder::class,
            EditHistorySeeder::class,
        ]);
    }
}
