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
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->unsignedBigInteger('user_id')->index('test_sessions_user_id_foreign');
            $table->string('event_id', 5)->nullable()->index('test_sessions_event_id_foreign');
            $table->string('session_token', 32)->unique();
            $table->string('current_step', 32)->default('form_data');
            $table->string('st30_version_id', 10)->nullable();
            $table->string('participant_name', 50)->nullable();
            $table->string('participant_background', 50)->nullable();
            $table->string('position', 25)->nullable();
            $table->boolean('is_completed')->default(false);
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->index(['current_step', 'is_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_sessions');
    }
};
