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
            $table->string('id')->primary();
            $table->string('question_id')->index('sjt_options_question_id_foreign');
            $table->char('option_letter', 1)->index();
            $table->text('option_text');
            $table->integer('score')->default(0);
            $table->string('competency_target')->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
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
