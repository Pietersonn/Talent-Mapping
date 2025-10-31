<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventParticipantsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('event_participants')->delete();

        DB::table('event_participants')->insert(array (
            0 =>
            array (
                'id' => 25,
                'event_id' => 'EVT25',
                'user_id' => 6,
                'test_completed' => 0,
                'results_sent' => 0,
                'created_at' => '2025-10-29 02:18:14',
                'updated_at' => '2025-10-29 02:18:14',
            ),
        ));


    }
}
