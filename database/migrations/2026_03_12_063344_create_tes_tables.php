<?php
// FILE: 2025_10_31_010600_create_tes_tables.php
// Tabel inti tes: sesi_tes, jawaban_st30, jawaban_tk, hasil_tes, permintaan_kirim_ulang
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // test_sessions → sesi_tes
        Schema::create('sesi_tes', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->unsignedBigInteger('id_pengguna')  // was: user_id
                  ->index('idx_sesi_tes_pengguna');
            $table->string('id_program', 10)->nullable() // was: event_id  (10 char sesuai program.id)
                  ->index('idx_sesi_tes_program');
            $table->string('token_sesi', 32)->unique(); // was: session_token
            $table->string('langkah_saat_ini', 32)->default('form_data'); // was: current_step
            $table->string('id_versi_st30', 10)->nullable(); // was: st30_version_id
            $table->string('nama_peserta', 50)->nullable();   // was: participant_name
            $table->string('latar_belakang', 100)->nullable(); // was: participant_background
            $table->string('jabatan', 25)->nullable();         // was: position
            $table->boolean('selesai')->default(false);        // was: is_completed
            $table->dateTime('selesai_pada')->nullable();      // was: completed_at
            $table->timestamps();

            $table->index(['langkah_saat_ini', 'selesai']);
            $table->foreign('id_pengguna', 'fk_sesi_tes_ke_pengguna')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('id_program', 'fk_sesi_tes_ke_program')
                  ->references('id')->on('program')
                  ->onUpdate('restrict')->onDelete('set null');
        });

        // st30_responses → jawaban_st30
        Schema::create('jawaban_st30', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('id_sesi', 5);              // was: session_id
            $table->string('id_versi_soal', 5)->index('idx_jawaban_st30_versi'); // was: question_version_id
            $table->unsignedInteger('nomor_tahap');    // was: stage_number
            $table->json('item_dipilih');              // was: selected_items
            $table->json('item_dikecualikan')->nullable(); // was: excluded_items
            $table->boolean('untuk_penilaian');        // was: for_scoring
            $table->unsignedInteger('waktu_respons')->nullable(); // was: response_time

            $table->index(['id_sesi', 'nomor_tahap'], 'idx_jawaban_st30_sesi_tahap');
            $table->index(['id_sesi', 'nomor_tahap', 'untuk_penilaian']);
            $table->foreign('id_sesi', 'fk_jawaban_st30_ke_sesi')
                  ->references('id')->on('sesi_tes')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('id_versi_soal', 'fk_jawaban_st30_ke_versi')
                  ->references('id')->on('versi_soal')
                  ->onUpdate('restrict')->onDelete('cascade');
        });

        // sjt_responses → jawaban_tk
        Schema::create('jawaban_tk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_sesi', 5);              // was: session_id
            $table->string('id_soal', 5)->index('idx_jawaban_tk_soal');   // was: question_id
            $table->string('id_versi_soal', 5)->index('idx_jawaban_tk_versi'); // was: question_version_id
            $table->unsignedInteger('nomor_halaman');  // was: page_number
            $table->char('pilihan_dipilih', 1);        // was: selected_option
            $table->unsignedInteger('waktu_respons')->nullable(); // was: response_time

            $table->index(['id_sesi', 'id_soal', 'nomor_halaman']);
            $table->unique(['id_sesi', 'id_soal'], 'uniq_sesi_soal');
            $table->foreign('id_sesi', 'fk_jawaban_tk_ke_sesi')
                  ->references('id')->on('sesi_tes')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('id_versi_soal', 'fk_jawaban_tk_ke_versi')
                  ->references('id')->on('versi_soal')
                  ->onUpdate('restrict')->onDelete('cascade');
        });

        // test_results → hasil_tes
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('id_sesi', 5)->unique();     // was: session_id
            $table->json('hasil_st30')->nullable();     // was: st30_results
            $table->json('hasil_tk')->nullable();       // was: sjt_results
            $table->string('tipologi_dominan', 5)->nullable(); // was: dominant_typology
            $table->timestamp('laporan_dibuat_pada')->nullable(); // was: report_generated_at
            $table->timestamp('email_terkirim_pada')->nullable(); // was: email_sent_at
            $table->string('path_pdf', 200)->nullable(); // was: pdf_path
            $table->timestamps();

            $table->index(['tipologi_dominan', 'email_terkirim_pada']);
            $table->foreign('id_sesi', 'fk_hasil_tes_ke_sesi')
                  ->references('id')->on('sesi_tes')
                  ->onUpdate('restrict')->onDelete('cascade');
        });

        // resend_requests → permintaan_kirim_ulang
        Schema::create('permintaan_kirim_ulang', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->unsignedBigInteger('id_pengguna')  // was: user_id
                  ->index('idx_kirim_ulang_pengguna');
            $table->string('id_hasil_tes', 5)          // was: test_result_id
                  ->index('idx_kirim_ulang_hasil');
            $table->timestamp('tanggal_permintaan')->useCurrent(); // was: request_date
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->unsignedBigInteger('disetujui_oleh')->nullable() // was: approved_by
                  ->index('idx_kirim_ulang_approver');
            $table->timestamp('disetujui_pada')->nullable(); // was: approved_at
            $table->text('alasan_penolakan')->nullable();    // was: rejection_reason
            $table->text('catatan_admin')->nullable();       // was: admin_notes
            $table->timestamps();

            $table->foreign('id_pengguna', 'fk_kirim_ulang_ke_pengguna')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('id_hasil_tes', 'fk_kirim_ulang_ke_hasil')
                  ->references('id')->on('hasil_tes')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('disetujui_oleh', 'fk_kirim_ulang_approver')
                  ->references('id')->on('pengguna')
                  ->onUpdate('restrict')->onDelete('set null');
        });

    }

    public function down(): void {
        Schema::dropIfExists('permintaan_kirim_ulang');
        Schema::dropIfExists('hasil_tes');
        Schema::dropIfExists('jawaban_tk');
        Schema::dropIfExists('jawaban_st30');
        Schema::dropIfExists('sesi_tes');
    }
};
