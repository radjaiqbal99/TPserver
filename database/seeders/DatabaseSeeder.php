<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => bcrypt('tpS123123'),
            'status' => '0',
            'remember_token' => Str::random(50)
        ]);

        DB::table('daftar_pegawais')->insert([
            
        ]);
    }
}
