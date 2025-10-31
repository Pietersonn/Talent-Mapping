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
        Schema::create('test_results', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('session_id', 5)->unique();
            $table->json('st30_results')->nullable();
            $table->json('sjt_results')->nullable();
            $table->string('dominant_typology', 5)->nullable();
            $table->timestamp('report_generated_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->string('pdf_path', 200)->nullable();
            $table->timestamps();

            $table->index(['dominant_typology', 'email_sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
