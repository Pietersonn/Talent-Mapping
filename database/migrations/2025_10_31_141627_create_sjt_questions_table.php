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
            $table->string('id')->primary();
            $table->string('version_id')->index('sjt_questions_version_id_foreign');
            $table->integer('number')->index();
            $table->text('question_text');
            $table->string('competency')->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['version_id', 'number'], 'idx_sjt_questions_version_number');
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
