<?php
// FILE: 2026_03_12_063338_create_program_tables.php
// Tabel yang FK ke pengguna: log_aktivitas, program, peserta_program
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // activity_logs → log_aktivitas
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pengguna')->nullable()->index();
            $table->string('aksi', 50);
            $table->text('keterangan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('properti')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['aksi', 'created_at']);
            $table->foreign('id_pengguna', 'fk_log_ke_pengguna')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('set null');
        });

        // events → program  (was: acara)
        Schema::create('program', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama', 100);
            $table->string('perusahaan', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('kode_program', 15)->unique();   // was: kode_acara
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedBigInteger('id_mitra')->nullable(); // tetap id_mitra (FK ke pengguna bermitra)
            $table->boolean('aktif')->default(true);
            $table->unsignedInteger('maks_peserta')->nullable();
            $table->timestamps();

            $table->index(['aktif', 'tanggal_mulai', 'tanggal_selesai']);
            $table->foreign('id_mitra', 'fk_program_ke_mitra')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('set null');
        });

        // event_participants → peserta_program  (was: peserta_acara)
        Schema::create('peserta_program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_program', 10);               // was: id_acara
            $table->unsignedBigInteger('id_pengguna');
            $table->boolean('tes_selesai')->default(false);
            $table->boolean('hasil_terkirim')->default(false);
            $table->timestamps();

            $table->unique(['id_program', 'id_pengguna']);
            $table->index(['tes_selesai', 'hasil_terkirim']);
            $table->foreign('id_program', 'fk_peserta_ke_program')
                  ->references('id')->on('program')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('id_pengguna', 'fk_peserta_ke_pengguna')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('peserta_program');
        Schema::dropIfExists('program');
        Schema::dropIfExists('log_aktivitas');
    }
};
