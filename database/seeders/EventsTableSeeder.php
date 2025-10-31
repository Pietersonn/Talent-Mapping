<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('events')->delete();

        DB::table('events')->insert(array (
            0 =>
            array (
                'id' => 'EVT25',
                'name' => 'BCTI Talent Assessment Program 2025',
                'company' => 'Business & Communication Training Institute',
                'description' => 'Business & Communication Training Institute Assessment 2025',
                'event_code' => 'BCTI2025',
                'start_date' => '2025-08-25',
                'end_date' => '2025-12-31',
                'pic_id' => 7,
                'is_active' => 1,
                'max_participants' => 1000,
                'created_at' => '2025-08-25 02:43:39',
                'updated_at' => '2025-09-06 02:43:04',
            ),
            1 =>
            array (
                'id' => 'EVT29',
                'name' => 'Squid Camp Vol.5',
                'company' => 'Dispora Kalsel',
                'description' => 'Squid Camp',
                'event_code' => 'SQ2025',
                'start_date' => '2025-08-30',
                'end_date' => '2025-10-31',
                'pic_id' => 7,
                'is_active' => 1,
                'max_participants' => 100,
                'created_at' => '2025-08-30 04:10:48',
                'updated_at' => '2025-10-29 06:55:16',
            ),
        ));


    }
}
