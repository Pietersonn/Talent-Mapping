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
        Schema::create('competency_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('competency_code', 30)->unique();
            $table->string('competency_name', 50)->index();
            $table->text('strength_description')->nullable();
            $table->text('weakness_description')->nullable();
            $table->text('improvement_activity')->nullable();
            $table->timestamps();
            $table->text('training_recommendations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_descriptions');
    }
};
