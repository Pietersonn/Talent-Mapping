<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengguna')->delete();

        DB::table('pengguna')->insert([
            [
                'id'                => 1,
                'nama'              => 'Muhammad Akmal',
                'email'             => 'admin@talentmapping.com',
                'nomor_telepon'     => '082252957870',
                'google_id'         => null,
                'email_verified_at' => null,
                'password'          => Hash::make('12345678'),
                'peran'             => 'admin',
                'aktif'             => 1,
                'remember_token'    => null,
                'created_at'        => '2025-08-19 04:12:11',
                'updated_at'        => '2025-10-02 03:29:00',
            ],
            [
                'id'                => 2,
                'nama'              => 'Muhammad Akmal',
                'email'             => 'mhammadakmalll@gmail.com',
                'nomor_telepon'     => '082252957879',
                'google_id'         => null,
                'email_verified_at' => null,
                'password'          => Hash::make('12345678'),
                'peran'             => 'peserta',
                'aktif'             => 1,
                'remember_token'    => null,
                'created_at'        => '2025-08-19 04:12:11',
                'updated_at'        => '2025-10-02 03:29:00',
            ],
        ]);
    }
}
