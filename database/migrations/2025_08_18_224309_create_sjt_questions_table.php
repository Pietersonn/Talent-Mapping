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
        Schema::create('sjt_questions', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('version_id', 5);
            $table->unsignedInteger('number');
            $table->text('question_text');
            $table->string('competency', 30);
            $table->unsignedInteger('page_number');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->unique(['version_id', 'number']);
            $table->index(['competency', 'page_number', 'is_active']);

            // Foreign keys
            $table->foreign('version_id')->references('id')->on('question_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sjt_questions');
    }
};
