<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilihanTkTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('pilihan_tk')->delete();
    }
}
