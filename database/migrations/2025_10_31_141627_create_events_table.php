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
        Schema::create('events', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('name', 100);
            $table->string('company', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('event_code', 15)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('pic_id')->nullable()->index('events_pic_id_foreign');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('max_participants')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
