<?php
// FILE: 2025_10_31_010200_create_sistem_tables.php
// Tabel sistem Laravel: session, jobs, password reset
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // sessions → sesi_pengguna
        Schema::create('sesi_pengguna', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // jobs → antrian_tugas
        Schema::create('antrian_tugas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // failed_jobs → antrian_gagal
        Schema::create('antrian_gagal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // password_reset_tokens → token_reset_sandi
        Schema::create('token_reset_sandi', function (Blueprint $table) {
            $table->string('email', 50)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->index(['token', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('token_reset_sandi');
        Schema::dropIfExists('antrian_gagal');
        Schema::dropIfExists('antrian_tugas');
        Schema::dropIfExists('sesi_pengguna');
    }
};
