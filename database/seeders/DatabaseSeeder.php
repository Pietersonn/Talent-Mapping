<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            PenggunaTableSeeder::class,
            VersiSoalTableSeeder::class,
            DeskripsiKompetensiTableSeeder::class,
            DeskripsiTipologiTableSeeder::class,
            SoalSt30TableSeeder::class,
            SoalTkTableSeeder::class,
            PilihanTkTableSeeder::class,
            AcaraTableSeeder::class,
            PesertaAcaraTableSeeder::class,
            PermintaanKirimUlangTableSeeder::class,
            TokenResetSandiTableSeeder::class,
            LogAktivitasTableSeeder::class,
            HasilTesTableSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
