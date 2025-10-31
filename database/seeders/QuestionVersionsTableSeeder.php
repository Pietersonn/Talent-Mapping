<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionVersionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('question_versions')->delete();

        DB::table('question_versions')->insert(array (
            0 =>
            array (
                'id' => 'SJV01',
                'version' => 1,
                'type' => 'sjt',
                'name' => 'SJT Version 1.0',
                'description' => 'Initial version of SJT Situational Judgment Test questions',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            1 =>
            array (
                'id' => 'STV01',
                'version' => 1,
                'type' => 'st30',
                'name' => 'ST-30 Version 1.0',
                'description' => 'Initial version of ST-30 Strength Typology questions',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
        ));


    }
}
