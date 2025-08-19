<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Use foreignId()
            $table->string('event_id', 5)->nullable();
            $table->string('session_token', 32)->unique();
            $table->enum('current_step', [
                'form_data',
                'st30_stage1',
                'st30_stage2',
                'st30_stage3',
                'st30_stage4',
                'sjt_page1',
                'sjt_page2',
                'sjt_page3',
                'sjt_page4',
                'sjt_page5',
                'completed'
            ])->default('form_data');
            $table->string('participant_name', 50)->nullable();
            $table->string('participant_background', 50)->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['current_step', 'is_completed']);

            // Foreign keys
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_sessions');
    }
};
