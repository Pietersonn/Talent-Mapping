<?php
// FILE: 2025_10_31_010400_create_acara_tables.php
// Tabel yang FK ke pengguna: log_aktivitas, acara, peserta_acara
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // activity_logs → log_aktivitas
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pengguna')->nullable()->index(); // was: user_id
            $table->string('aksi', 50);                 // was: action
            $table->text('keterangan')->nullable();     // was: description
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('properti')->nullable();       // was: properties
            $table->timestamp('created_at')->useCurrent();

            $table->index(['aksi', 'created_at']);
            $table->foreign('id_pengguna', 'fk_log_ke_pengguna')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('set null');
        });

        // events → acara
        Schema::create('acara', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('nama', 100);                // was: name
            $table->string('perusahaan', 100)->nullable(); // was: company
            $table->text('deskripsi')->nullable();      // was: description
            $table->string('kode_acara', 15)->unique(); // was: event_code
            $table->date('tanggal_mulai');              // was: start_date
            $table->date('tanggal_selesai');            // was: end_date
            $table->unsignedBigInteger('id_pic')->nullable(); // was: pic_id
            $table->boolean('aktif')->default(true);   // was: is_active
            $table->unsignedInteger('maks_peserta')->nullable(); // was: max_participants
            $table->timestamps();

            $table->index(['aktif', 'tanggal_mulai', 'tanggal_selesai']);
            $table->foreign('id_pic', 'fk_acara_ke_pengguna')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('set null');
        });

        // event_participants → peserta_acara
        Schema::create('peserta_acara', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_acara', 10);             // was: event_id (10 char sesuai acara.id)
            $table->unsignedBigInteger('id_pengguna'); // was: user_id
            $table->boolean('tes_selesai')->default(false);    // was: test_completed
            $table->boolean('hasil_terkirim')->default(false); // was: results_sent
            $table->timestamps();

            $table->unique(['id_acara', 'id_pengguna']);
            $table->index(['tes_selesai', 'hasil_terkirim']);
            $table->foreign('id_acara', 'fk_peserta_ke_acara')
                  ->references('id')->on('acara')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('id_pengguna', 'fk_peserta_ke_pengguna')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('cascade');
        });

    }

    public function down(): void {
        Schema::dropIfExists('peserta_acara');
        Schema::dropIfExists('acara');
        Schema::dropIfExists('log_aktivitas');
    }
};
