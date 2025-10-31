<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('st30_responses', function (Blueprint $table) {
            $table->foreign(['question_version_id'])->references(['id'])->on('question_versions')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['session_id'])->references(['id'])->on('test_sessions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('st30_responses', function (Blueprint $table) {
            $table->dropForeign('st30_responses_question_version_id_foreign');
            $table->dropForeign('st30_responses_session_id_foreign');
        });
    }
};
