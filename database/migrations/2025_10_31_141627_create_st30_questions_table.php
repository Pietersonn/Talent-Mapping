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
        Schema::create('st30_questions', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('version_id', 5);
            $table->unsignedInteger('number');
            $table->text('statement');
            $table->string('typology_code', 5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['version_id', 'is_active', 'number'], 'idx_st30_questions_version_active_number');
            $table->index(['typology_code', 'is_active']);
            $table->unique(['version_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('st30_questions');
    }
};
