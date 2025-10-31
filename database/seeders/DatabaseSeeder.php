<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Matikan pengecekan foreign key saat seeding
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $this->call([
            // Panggil SEMUA seeder yang baru kita buat
            UsersTableSeeder::class,
            EventsTableSeeder::class,
            QuestionVersionsTableSeeder::class,
            CompetencyDescriptionsTableSeeder::class,
            TypologyDescriptionsTableSeeder::class,
            EventParticipantsTableSeeder::class,
            ResendRequestsTableSeeder::class,
            ActivityLogsTableSeeder::class,
            PasswordResetTokensTableSeeder::class,
            SjtQuestionsTableSeeder::class,
            SjtOptionsTableSeeder::class,
            SjtResponsesTableSeeder::class,
            St30QuestionsTableSeeder::class,
            St30ResponsesTableSeeder::class,
            TestResultsTableSeeder::class,
        ]);

        // Nyalakan kembali
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        $this->call(EventParticipantsTableSeeder::class);
    }
}
