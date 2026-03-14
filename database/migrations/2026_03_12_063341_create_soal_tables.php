<?php
// FILE: 2025_10_31_010500_create_soal_tables.php
// Tabel soal: soal_st30, soal_tk, pilihan_tk
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // st30_questions → soal_st30
        Schema::create('soal_st30', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('id_versi', 5);              // was: version_id
            $table->unsignedInteger('nomor');           // was: number
            $table->text('pernyataan');                 // was: statement
            $table->string('kode_tipologi', 5);         // was: typology_code
            $table->boolean('aktif')->default(true);   // was: is_active
            $table->timestamps();

            $table->index(['id_versi', 'aktif', 'nomor'], 'idx_soal_st30_versi_aktif_nomor');
            $table->index(['kode_tipologi', 'aktif']);
            $table->unique(['id_versi', 'nomor']);
            $table->foreign('id_versi', 'fk_soal_st30_ke_versi')
                  ->references('id')->on('versi_soal')
                  ->onUpdate('restrict')->onDelete('cascade');
        });

        // sjt_questions → soal_tk  (TK = Talent Kompetensi)
        Schema::create('soal_tk', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_versi')->index('idx_soal_tk_versi'); // was: version_id
            $table->integer('nomor')->index();          // was: number
            $table->text('teks_pertanyaan');            // was: question_text
            $table->string('kode_kompetensi')->index(); // was: competency
            $table->boolean('aktif')->default(true);   // was: is_active
            $table->timestamps();

            $table->index(['id_versi', 'nomor'], 'idx_soal_tk_versi_nomor');
            $table->foreign('id_versi', 'fk_soal_tk_ke_versi')
                  ->references('id')->on('versi_soal')
                  ->onUpdate('restrict')->onDelete('cascade');
        });

        // sjt_options → pilihan_tk
        Schema::create('pilihan_tk', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_soal')->index('idx_pilihan_tk_soal'); // was: question_id
            $table->char('huruf_pilihan', 1)->index(); // was: option_letter
            $table->text('teks_pilihan');              // was: option_text
            $table->integer('skor')->default(0);       // was: score
            $table->string('target_kompetensi')->index(); // was: competency_target
            $table->boolean('aktif')->default(true);   // was: is_active
            $table->timestamps();

            $table->foreign('id_soal', 'fk_pilihan_ke_soal_tk')
                  ->references('id')->on('soal_tk')
                  ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('target_kompetensi', 'fk_pilihan_ke_kompetensi')
                  ->references('kode_kompetensi')->on('deskripsi_kompetensi')
                  ->onUpdate('cascade')->onDelete('restrict');
        });

    }

    public function down(): void {
        Schema::dropIfExists('pilihan_tk');
        Schema::dropIfExists('soal_tk');
        Schema::dropIfExists('soal_st30');
    }
};
