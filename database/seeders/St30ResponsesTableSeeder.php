<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class St30ResponsesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('st30_responses')->delete();

        DB::table('st30_responses')->insert(array (
            0 =>
            array (
                'id' => 'STR01',
                'session_id' => 'TS623',
                'question_version_id' => 'STV01',
                'stage_number' => 1,
                'selected_items' => '["ST01","ST02","ST03","ST04","ST05","ST06"]',
                'excluded_items' => NULL,
                'for_scoring' => 1,
                'response_time' => NULL,
            ),
            1 =>
            array (
                'id' => 'STR02',
                'session_id' => 'TS623',
                'question_version_id' => 'STV01',
                'stage_number' => 2,
                'selected_items' => '["ST07","ST08","ST09","ST10","ST11"]',
                'excluded_items' => NULL,
                'for_scoring' => 1,
                'response_time' => NULL,
            ),
            2 =>
            array (
                'id' => 'STR03',
                'session_id' => 'TS623',
                'question_version_id' => 'STV01',
                'stage_number' => 3,
                'selected_items' => '["ST12","ST13","ST14","ST15","ST16"]',
                'excluded_items' => NULL,
                'for_scoring' => 0,
                'response_time' => NULL,
            ),
            3 =>
            array (
                'id' => 'STR04',
                'session_id' => 'TS623',
                'question_version_id' => 'STV01',
                'stage_number' => 4,
                'selected_items' => '["ST17","ST18","ST19","ST20","ST21","ST22"]',
                'excluded_items' => NULL,
                'for_scoring' => 0,
                'response_time' => NULL,
            ),
        ));


    }
}
