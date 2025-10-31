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
        Schema::create('question_versions', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->unsignedInteger('version');
            $table->enum('type', ['st30', 'sjt']);
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();

            $table->unique(['type', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_versions');
    }
};
