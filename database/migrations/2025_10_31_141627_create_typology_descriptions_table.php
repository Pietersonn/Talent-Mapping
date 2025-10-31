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
        Schema::create('typology_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('typology_code', 5)->unique();
            $table->string('typology_name', 30)->index();
            $table->text('strength_description')->nullable();
            $table->text('weakness_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typology_descriptions');
    }
};
