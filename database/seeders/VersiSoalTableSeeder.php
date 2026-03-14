<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VersiSoalTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('versi_soal')->delete();

        DB::table('versi_soal')->insert([
            [
                'id'         => 'TKV01',
                'versi'      => 1,
                'jenis'      => 'tk',
                'nama'       => 'Talent Kompetensi v1.0',
                'deskripsi'  => 'Versi pertama soal Talent Kompetensi (TK)',
                'aktif'      => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ],
            [
                'id'         => 'STV01',
                'versi'      => 1,
                'jenis'      => 'st30',
                'nama'       => 'ST-30 v1.0',
                'deskripsi'  => 'Versi pertama soal ST-30 Strength Typology',
                'aktif'      => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ],
        ]);
    }
}
