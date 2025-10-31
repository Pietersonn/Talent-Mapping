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
        Schema::create('st30_responses', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('session_id', 5);
            $table->string('question_version_id', 5)->index('idx_st30_responses_version');
            $table->unsignedInteger('stage_number');
            $table->json('selected_items');
            $table->json('excluded_items')->nullable();
            $table->boolean('for_scoring');
            $table->unsignedInteger('response_time')->nullable();

            $table->index(['session_id', 'stage_number'], 'idx_st30_responses_session_stage');
            $table->index(['session_id', 'stage_number', 'for_scoring']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('st30_responses');
    }
};
