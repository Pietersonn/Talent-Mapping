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
            $table->string('id', 5)->primary();
            $table->string('session_id', 5);
            $table->string('question_id', 5);
            $table->string('question_version_id', 5);
            $table->unsignedInteger('page_number');
            $table->char('selected_option', 1);
            $table->unsignedInteger('response_time')->nullable();

            // Indexes
            $table->index(['session_id', 'question_id', 'page_number']);

            // Foreign keys
            $table->foreign('session_id')->references('id')->on('test_sessions')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('sjt_questions')->onDelete('cascade');
            $table->foreign('question_version_id')->references('id')->on('question_versions')->onDelete('cascade');
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
