<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 5);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Use foreignId()
            $table->boolean('test_completed')->default(false);
            $table->boolean('results_sent')->default(false);
            $table->timestamps();

            // Indexes
            $table->unique(['event_id', 'user_id']);
            $table->index(['test_completed', 'results_sent']);

            // Foreign keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
