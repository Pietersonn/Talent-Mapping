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
        Schema::create('resend_requests', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->unsignedBigInteger('user_id')->index('resend_requests_user_id_foreign');
            $table->string('test_result_id', 5)->index('resend_requests_test_result_id_foreign');
            $table->timestamp('request_date')->useCurrent();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->unsignedBigInteger('approved_by')->nullable()->index('resend_requests_approved_by_foreign');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resend_requests');
    }
};
