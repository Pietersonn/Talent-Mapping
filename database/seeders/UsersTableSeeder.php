<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'phone_number' => NULL,
                'google_id' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$qphKXQgRcNZaqlZykkMtkeIpnFWPTvF4cFj40EpMby53yIGxPmXBW',
                'role' => 'admin',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-08-19 04:12:11',
                'updated_at' => '2025-10-02 03:29:00',
            ),
            1 =>
            array (
                'id' => 6,
                'name' => 'Muhammad Akmal',
                'email' => 'mhammadakmall@gmail.com',
                'phone_number' => '082252957879',
                'google_id' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$RdYHoNNvuXCSSxNM6lvMJ.EVZ.q34RigPpZ3zQcOfABjXbh3EDBN6',
                'role' => 'user',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-09-04 04:06:06',
                'updated_at' => '2025-10-31 02:07:32',
            ),
            2 =>
            array (
                'id' => 7,
                'name' => 'Pieter',
                'email' => 'pietersonnn@gmail.com',
                'phone_number' => NULL,
                'google_id' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$MGTwHAUVILKWB5stgrpizOhvIqWgnnaCcn8lrezHQMQgMrFHnMFji',
                'role' => 'pic',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-09-06 02:40:08',
                'updated_at' => '2025-09-06 02:40:08',
            ),
            3 =>
            array (
                'id' => 8,
                'name' => 'Muhammad Faisal',
                'email' => 'akmal.parlon@gmail.com',
                'phone_number' => NULL,
                'google_id' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$veSsTjY4EV0tURGfbsQ5IeIMzzB.yvPDxmJ0XGxYTbZb8dCPwT2hy',
                'role' => 'user',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-09-22 04:28:52',
                'updated_at' => '2025-09-22 04:28:52',
            ),
            4 =>
            array (
                'id' => 9,
                'name' => 'Tito Setiyanto',
                'email' => 'fetyf115@gmail.com',
                'phone_number' => NULL,
                'google_id' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$3sJlWYaD/oVvcFvYIPAV9e0PO/ibBpJH/tZD1WR5YlT6E5YhevW72',
                'role' => 'user',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-09-23 08:13:28',
                'updated_at' => '2025-09-26 01:25:37',
            ),
            5 =>
            array (
                'id' => 10,
                'name' => 'Fetty Fatimah',
                'email' => 'bcti@hasnurcentre.com',
                'phone_number' => NULL,
                'google_id' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$12$RjsHilr0Zr1Nn2SIb5zNHe8bLRDTiXcqZnYNKiyosZdYQWl1wFs8m',
                'role' => 'user',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2025-09-25 12:46:37',
                'updated_at' => '2025-09-29 02:40:13',
            ),
            6 =>
            array (
                'id' => 11,
                'name' => 'Muhammad Akmal',
                'email' => 'mhammadakmalll@gmail.com',
                'phone_number' => NULL,
                'google_id' => '106390106697241079700',
                'email_verified_at' => NULL,
                'password' => '$2y$12$CMkuHsfcI7kCfCV.ZFLInen3plzu/Nz/xUlt3gSJ6KzPdq2dRtKr6',
                'role' => 'user',
                'is_active' => 1,
                'remember_token' => 'MkRCNRVZtCQdzDGSaO2DXT96nTxFzxBQSIEQRabce5okQWipZNatp4RjKIj0',
                'created_at' => '2025-10-31 02:38:31',
                'updated_at' => '2025-10-31 02:38:31',
            ),
        ));


    }
}
