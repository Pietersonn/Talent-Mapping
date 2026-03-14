<?php
// FILE: 2025_10_31_010300_create_referensi_tables.php
// Tabel referensi tanpa FK: versi_soal, deskripsi_kompetensi, deskripsi_tipologi
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // question_versions → versi_soal
        Schema::create('versi_soal', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->unsignedInteger('versi');           // was: version
            $table->enum('jenis', ['st30', 'tk']);      // was: type  (nilai 'sjt' → 'tk')
            $table->string('nama', 50);                 // was: name
            $table->text('deskripsi')->nullable();      // was: description
            $table->boolean('aktif')->default(false)->index(); // was: is_active
            $table->timestamps();

            $table->unique(['jenis', 'versi']);
        });

        // competency_descriptions → deskripsi_kompetensi
        Schema::create('deskripsi_kompetensi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_kompetensi', 30)->unique(); // was: competency_code
            $table->string('nama_kompetensi', 50)->index();  // was: competency_name
            $table->text('deskripsi_kekuatan')->nullable();  // was: strength_description
            $table->text('deskripsi_kelemahan')->nullable(); // was: weakness_description
            $table->text('aktivitas_pengembangan')->nullable(); // was: improvement_activity
            $table->text('rekomendasi_pelatihan')->nullable();  // was: training_recommendations
            $table->timestamps();
        });

        // typology_descriptions → deskripsi_tipologi
        Schema::create('deskripsi_tipologi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_tipologi', 5)->unique(); // was: typology_code
            $table->string('nama_tipologi', 30)->index(); // was: typology_name
            $table->text('deskripsi_kekuatan')->nullable(); // was: strength_description
            $table->text('deskripsi_kelemahan')->nullable(); // was: weakness_description
            $table->timestamps();
        });

    }

    public function down(): void {
        Schema::dropIfExists('deskripsi_tipologi');
        Schema::dropIfExists('deskripsi_kompetensi');
        Schema::dropIfExists('versi_soal');
    }
};
