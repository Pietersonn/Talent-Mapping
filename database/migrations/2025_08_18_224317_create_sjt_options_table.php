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
        Schema::create('sjt_options', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('question_id', 5);
            $table->char('option_letter', 1);
            $table->text('option_text');
            $table->unsignedInteger('score');
            $table->string('competency_target', 30)->nullable();

            // Indexes
            $table->unique(['question_id', 'option_letter']);
            $table->index('score');

            // Foreign keys
            $table->foreign('question_id')->references('id')->on('sjt_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sjt_options');
    }
};
