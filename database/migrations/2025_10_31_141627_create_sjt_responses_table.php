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
        Schema::create('sjt_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id', 5);
            $table->string('question_id', 5)->index('sjt_responses_question_id_foreign');
            $table->string('question_version_id', 5)->index('sjt_responses_question_version_id_foreign');
            $table->unsignedInteger('page_number');
            $table->char('selected_option', 1);
            $table->unsignedInteger('response_time')->nullable();

            $table->index(['session_id', 'question_id', 'page_number']);
            $table->unique(['session_id', 'question_id'], 'uniq_session_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sjt_responses');
    }
};
