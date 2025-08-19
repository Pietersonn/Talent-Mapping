<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resend_requests', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Use foreignId()
            $table->string('test_result_id', 5);
            $table->timestamp('request_date')->useCurrent();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Use foreignId()
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');

            // Foreign keys
            $table->foreign('test_result_id')->references('id')->on('test_results')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resend_requests');
    }
};
