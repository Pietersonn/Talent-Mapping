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
        Schema::table('resend_requests', function (Blueprint $table) {
            $table->foreign(['approved_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['test_result_id'])->references(['id'])->on('test_results')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resend_requests', function (Blueprint $table) {
            $table->dropForeign('resend_requests_approved_by_foreign');
            $table->dropForeign('resend_requests_test_result_id_foreign');
            $table->dropForeign('resend_requests_user_id_foreign');
        });
    }
};
