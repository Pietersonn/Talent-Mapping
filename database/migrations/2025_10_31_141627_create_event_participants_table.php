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
        Schema::create('event_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_id', 5);
            $table->unsignedBigInteger('user_id')->index('event_participants_user_id_foreign');
            $table->boolean('test_completed')->default(false);
            $table->boolean('results_sent')->default(false);
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
            $table->index(['test_completed', 'results_sent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
