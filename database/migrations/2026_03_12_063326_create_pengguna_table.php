<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 50);
            $table->string('email', 50)->unique();
            $table->string('nomor_telepon', 15)->nullable();
            $table->string('google_id', 191)->nullable()->index('idx_pengguna_google_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60);
            $table->enum('peran', ['peserta', 'mitra', 'admin'])->default('peserta');
            $table->boolean('aktif')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->index(['peran', 'aktif']);
        });
    }

    public function down(): void { Schema::dropIfExists('pengguna'); }
};
