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
        Schema::table('sjt_options', function (Blueprint $table) {
            $table->foreign(['competency_target'], 'fk_sjt_options_competency')->references(['competency_code'])->on('competency_descriptions')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['question_id'], 'fk_sjt_options_question')->references(['id'])->on('sjt_questions')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['question_id'])->references(['id'])->on('sjt_questions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sjt_options', function (Blueprint $table) {
            $table->dropForeign('fk_sjt_options_competency');
            $table->dropForeign('fk_sjt_options_question');
            $table->dropForeign('sjt_options_question_id_foreign');
        });
    }
};
