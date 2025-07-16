<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('users')->insert([
            [
                'name'              => 'Administrator',
                'username'          => 'admin',
                'role'              => 'ADMIN',
                'email'             => 'admin@example.com',
                'email_verified_at' => $now,
                'password'          => Hash::make('password'),   // ganti di production!
                'avatar'            => null,
                'last_login_at'     => null,
                'is_active'         => true,
                'remember_token'    => Str::random(10),
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'name'              => 'Demo Contributor',
                'username'          => 'demo_user',
                'role'              => 'CONTRIBUTOR',
                'email'             => 'contrib@example.com',
                'email_verified_at' => null,
                'password'          => Hash::make('password'),
                'avatar'            => null,
                'last_login_at'     => null,
                'is_active'         => true,
                'remember_token'    => Str::random(10),
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ]);
    }
}
