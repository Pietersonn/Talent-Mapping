<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// Tambahkan baris ini
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('users')->delete();

        DB::table('users')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Muhammad Akmal',
                'email' => 'admin@talentmapping.com',
                'phone_number' => '082252957870',
                'google_id' => NULL,
                'email_verified_at' => NULL,
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-08-19 04:12:11',
                'updated_at' => '2025-10-02 03:29:00',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Muhammad Akmal',
                'email' => 'mhammadakmalll@gmail.com',
                'phone_number' => '082252957879',
                'google_id' => NULL,
                'email_verified_at' => NULL,

                'password' => Hash::make('12345678'),
                'role' => 'user',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-08-19 04:12:11',
                'updated_at' => '2025-10-02 03:29:00',
            ),
        ));
    }
}
