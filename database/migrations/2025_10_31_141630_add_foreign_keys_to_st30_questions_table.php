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
        Schema::table('st30_questions', function (Blueprint $table) {
            $table->foreign(['version_id'])->references(['id'])->on('question_versions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('st30_questions', function (Blueprint $table) {
            $table->dropForeign('st30_questions_version_id_foreign');
        });
    }
};
