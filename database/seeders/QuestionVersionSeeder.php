<?php

namespace Database\Seeders;

use App\Models\QuestionVersion;
use Illuminate\Database\Seeder;

class QuestionVersionSeeder extends Seeder
{
    public function run(): void
    {
        $versions = [
            [
                'id' => 'STV01',
                'name' => 'ST-30 Version 1.0',
                'type' => 'st30',
                'version' => 1,
                'description' => 'Initial version of ST-30 Strength Typology questions',
                'is_active' => true,
            ],
            [
                'id' => 'SJV01',
                'name' => 'SJT Version 1.0',
                'type' => 'sjt',
                'version' => 1,
                'description' => 'Initial version of SJT Situational Judgment Test questions',
                'is_active' => true,
            ]
        ];

        foreach ($versions as $version) {
            QuestionVersion::updateOrCreate(
                ['id' => $version['id']],
                $version
            );
        }
    }
}
