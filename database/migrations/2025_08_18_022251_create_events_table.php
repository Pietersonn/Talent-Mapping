<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('event_code', 15)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('pic_id')->nullable()->constrained('users')->onDelete('set null'); // Use foreignId()
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('max_participants')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
