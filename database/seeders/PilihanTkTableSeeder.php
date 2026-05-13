<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilihanTkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kosongkan tabel sebelum diisi ulang
        DB::table('pilihan_tk')->delete();

        // Siapkan array data pilihan
        $options = [
            // TK001: Bagaimana cara kamu mengatur waktu untuk tugas yang butuh fokus tinggi? (SM)
            ['id_soal' => 'TK001', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Bikin jadwal dengan jeda istirahat teratur biar fokus nggak cepat habis.', 'skor' => 4, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK001', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Kerjain terus tanpa jeda biar cepat selesai, istirahat belakangan.', 'skor' => 3, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK001', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Mengerjakan semuanya apa adanya biar semua selesai.', 'skor' => 2, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK001', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Kerjain sesuai mood, asal nggak terlalu banyak gangguan.', 'skor' => 1, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK001', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mulai kerja kalau benar-benar mood aja, nggak pengen buru-buru.', 'skor' => 0, 'target_kompetensi' => 'SM', 'aktif' => 1],

            // TK002: Kalau ada situasi bikin emosi di tempat kerja, gimana caramu merespons? (SM)
            ['id_soal' => 'TK002', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Menyalurkan emosi untuk melepaskan kekesalan', 'skor' => 0, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK002', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Meninggalkan segera tempat agar tidak terpancing emosi', 'skor' => 1, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK002', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Diam saja namun memendam emosi', 'skor' => 2, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK002', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Alihkan perhatian ke hal lain dulu, baru kembali menghadapinya', 'skor' => 3, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK002', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mengkontrol emosi dalam diri dan menghadapi dengan tenang', 'skor' => 4, 'target_kompetensi' => 'SM', 'aktif' => 1],

            // TK003: Saat menghadapi masalah yang belum pernah kamu temui sebelumnya, gimana caramu mencari solusinya (TS)
            ['id_soal' => 'TK003', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya menunggu bantuan dari orang lain', 'skor' => 0, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK003', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya akan coba menyelesaikan semaksimal mugkin yang bisa saya lakukan', 'skor' => 1, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK003', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya akan mencari informasi umum terlebih dahulu mengenai permasalahan', 'skor' => 2, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK003', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya mencari solusi dengan riset lebih dalam atau diskusi dengan orang yang lebih paham.', 'skor' => 3, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK003', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya menganalisis masalah dengan riset serta diskusi dan mencoba beberapa pendekatan sampai menemukan solusi terbaik', 'skor' => 4, 'target_kompetensi' => 'TS', 'aktif' => 1],

            // TK004: Bagaimana kamu mengevaluasi keputusan yang sudah diambil? (TS)
            ['id_soal' => 'TK004', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Menganalisis hasil dan mencocokkannya dengan tujuan awal, lalu membuat opsi perbaikan', 'skor' => 4, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK004', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya akan menanyakan dampak dari keputusan saya terhadap tim', 'skor' => 3, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK004', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya akan mencoba melihat hasil dari keputusan saya dan memikirkan apa yang bisa diperbaiki', 'skor' => 2, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK004', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya selalu berpikiran berlebihan mengenai keputusan yang sudah saya ambil, saya masih ragu apakah itu benar, kurang tepat, atau salah', 'skor' => 1, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK004', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya sudah yakin dengan keputusan yang sudah saya ambil', 'skor' => 0, 'target_kompetensi' => 'TS', 'aktif' => 1],

            // TK005: Saat kamu ingin menyampaikan ide baru kepada tim, apa yang biasanya kamu lakukan? (CIA)
            ['id_soal' => 'TK005', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'saya ragu terhadap ide yang saya miliki jadi lebih memilih diam saja', 'skor' => 0, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK005', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya menyampaikan apa yang terlintas di benak saya', 'skor' => 1, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK005', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya berusaha menyakinkan tim bahwa ide saya adalah yang terbaik', 'skor' => 2, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK005', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengajak tim berdiskusi untuk mendapatkan masukan tentang ide saya', 'skor' => 3, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK005', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Menyampaikan ide secara santai sambil mendengar pendapat dari tim.', 'skor' => 4, 'target_kompetensi' => 'CIA', 'aktif' => 1],

            // TK006: Saat ada diskusi kelompok, bagaimana kamu memastikan setiap orang mendapat kesempatan bicara? (CIA)
            ['id_soal' => 'TK006', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mengajukan pertanyaan agar rekan kerja merasa didengarkan dan terlibat.', 'skor' => 0, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK006', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Memberikan ruang bagi rekan yang ingin berbicara, dan tidak mendominasi percakapan.', 'skor' => 1, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK006', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Menyimak dengan aktif dan mengundang pendapat dari mereka yang lebih pendiam.', 'skor' => 2, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK006', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Memastikan suasana diskusi nyaman agar semua orang merasa bebas berbicara.', 'skor' => 3, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK006', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Memberikan tanggapan positif setiap kali mereka menyampaikan ide.', 'skor' => 4, 'target_kompetensi' => 'CIA', 'aktif' => 1],

            // TK007: Jika kamu bekerja dengan rekan yang memiliki cara atau pandangan berbeda, bagaimana kamu menyikapinya? (WWO)
            ['id_soal' => 'TK007', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mencari kesempatan untuk belajar dari sudut pandang atau pendekatan mereka.', 'skor' => 1, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK007', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menghormati cara kerja mereka dan beradaptasi jika perlu untuk mencapai tujuan tim.', 'skor' => 2, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK007', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Menyampaikan ide atau cara yang kamu yakini secara terbuka dan sopan.', 'skor' => 3, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK007', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Berdiskusi dengan rekan tersebut untuk mencari cara kerja yang selaras.', 'skor' => 4, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK007', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Fokus pada hasil akhir dan fleksibel dengan pendekatan mereka.', 'skor' => 0, 'target_kompetensi' => 'WWO', 'aktif' => 1],

            // TK008: Ketika tugas dibagi dalam tim, apa yang biasanya kamu lakukan? (WWO)
            ['id_soal' => 'TK008', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya akan menunggu yang lain bergerak baru saya mengikuti', 'skor' => 0, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK008', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya lebih suka mengerjakan sendiri semuanya', 'skor' => 1, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK008', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'saya fokus menyelesaikan bagian tugas yang diberikan kepada saya', 'skor' => 2, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK008', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Menanyakan apakah ada yang butuh bantuan dalam menyelesaikan tugas.', 'skor' => 3, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK008', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya akan mencari penyelesaikan tugas yang efisien dan memastikan tugas selesai tepat waktu.', 'skor' => 4, 'target_kompetensi' => 'WWO', 'aktif' => 1],

            // TK009: Bagaimana kamu menjaga motivasi dalam bekerja, terutama saat menghadapi tantangan? (CA)
            ['id_soal' => 'TK009', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mengingat tujuan jangka panjang dan dampak positif dari pekerjaan ini.', 'skor' => 4, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK009', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Mengambil jeda sejenak untuk refleksi, lalu kembali dengan energi baru.', 'skor' => 3, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK009', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Membuat daftar tujuan harian untuk menjaga semangat bekerja.', 'skor' => 1, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK009', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mencari inspirasi dari rekan kerja atau mentor untuk memotivasi diri.', 'skor' => 2, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK009', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Fokus pada aspek-aspek pekerjaan yang paling kamu nikmati.', 'skor' => 0, 'target_kompetensi' => 'CA', 'aktif' => 1],

            // TK010: Saat kamu menghadapi kesulitan dalam pekerjaan, apa yang biasanya kamu lakukan? (CA)
            ['id_soal' => 'TK010', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mencari solusi secara mandiri dan mencoba beberapa pendekatan berbeda.', 'skor' => 1, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK010', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Bertanya atau berdiskusi dengan rekan untuk menemukan jalan keluar bersama.', 'skor' => 4, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK010', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Melakukan riset tambahan untuk memahami masalah dengan lebih baik.', 'skor' => 3, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK010', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Memprioritaskan penyelesaian masalah dengan langkah-langkah kecil.', 'skor' => 0, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK010', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mengambil waktu sejenak untuk menyusun strategi sebelum bertindak.', 'skor' => 2, 'target_kompetensi' => 'CA', 'aktif' => 1],

            // TK011: Bagaimana kamu menjaga semangat dan motivasi dalam tim saat menghadapi tantangan? (L)
            ['id_soal' => 'TK011', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Menyemangati tim dengan mengingatkan tujuan bersama dan kontribusi tiap orang.', 'skor' => 4, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK011', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menyediakan dukungan dengan berbicara positif dan memotivasi secara personal.', 'skor' => 3, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK011', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Mengajak tim untuk beristirahat sejenak dan kembali bekerja dengan energi baru.', 'skor' => 2, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK011', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Membangun lingkungan kerja yang ramah dan mendukung.', 'skor' => 1, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK011', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Menjadi contoh dengan menunjukkan sikap tenang dan percaya diri.', 'skor' => 0, 'target_kompetensi' => 'L', 'aktif' => 1],

            // TK012: Ketika tim membutuhkan seseorang untuk bertanggung jawab pada suatu proyek, bagaimana responsmu? (L)
            ['id_soal' => 'TK012', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya akan langsung siap dan bersemangat untuk mengambil tanggung jawab tersebut.', 'skor' => 4, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK012', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menyusun strategi bersama dan musyawarah untuk menentukan siapa yang cocok untuk bertanggung jawab', 'skor' => 3, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK012', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya akan bersedia membantu, tetapi perlu melihat dulu detail proyek dan beban kerja saya saat ini.', 'skor' => 2, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK012', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengamati kebutuhan proyek dan memberikan dukungan sesuai yang dibutuhkan.', 'skor' => 1, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK012', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya kurang tertarik untuk mengambil tanggung jawab proyek', 'skor' => 0, 'target_kompetensi' => 'L', 'aktif' => 1],

            // TK013: Bagaimana kamu menginspirasi rekan kerja melalui tindakan dan sikap sehari-hari? (L)
            ['id_soal' => 'TK013', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Memberikan contoh dengan bekerja keras dan berfokus pada hasil yang berkualitas.', 'skor' => 4, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK013', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menunjukkan empati dan kepedulian terhadap kesejahteraan tim.', 'skor' => 3, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK013', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya sering memberikan pujian dan apresiasi kepada rekan kerja atas prestasi yang mereka capai.', 'skor' => 4, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK013', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya hanya akan memberikan bantuan jika diminta oleh rekan kerja.', 'skor' => 3, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK013', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya merasa sulit untuk berinteraksi dengan orang lain dan lebih suka bekerja sendiri.', 'skor' => 3, 'target_kompetensi' => 'L', 'aktif' => 1],

            // TK014: Bagaimana kamu menunjukkan rasa percaya diri saat berinteraksi dengan rekan kerja baru? (SE)
            ['id_soal' => 'TK014', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya biasanya pasif dan lebih memilih menyendiri', 'skor' => 0, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK014', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya biasanya diam saja dan lebih banyak mendengarkan', 'skor' => 1, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK014', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya berusaha memulai percakapan, namun topiknya masih terbatas', 'skor' => 2, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK014', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya aktif bertanya dan memberikan pendapat', 'skor' => 3, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK014', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya selalu berusaha menjadi yang pertama menyapa dan memulai pembicaraan', 'skor' => 4, 'target_kompetensi' => 'SE', 'aktif' => 1],

            // TK015: Bagaimana kamu memperlakukan kegagalan atau kesalahan dalam pekerjaan? (SE)
            ['id_soal' => 'TK015', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya akan menyembunyikan kesalahan saya dan berharap tidak ada yang menyadarinya', 'skor' => 0, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK015', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya akan sangat malu dengan kesalahan saya dan sulit untuk melupakannya', 'skor' => 1, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK015', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya mengakui kesalahan saya, namun saya kurang suka jika orang lain menyebutnya dan mengkritik saya', 'skor' => 2, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK015', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya akan meminta maaf atas kesalahan saya dan berusaha untuk memperbaikinya secepat mungkin.', 'skor' => 3, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK015', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya akan menganalisis kesalahan saya untuk memahami apa yang salah dan bagaimana mencegahnya terulang.', 'skor' => 4, 'target_kompetensi' => 'SE', 'aktif' => 1],

            // TK016: Ketika menghadapi situasi baru yang memerlukan keputusan cepat, apa yang biasanya kamu lakukan? (SE)
            ['id_soal' => 'TK016', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Membuat keputusan berdasarkan keyakinan diri dan pengalaman yang relevan.', 'skor' => 2, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK016', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menganalisis situasi dengan cepat dan mengambil langkah yang paling logis.', 'skor' => 4, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK016', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Berdiskusi dengan rekan atau atasan untuk memastikan keputusan yang diambil tepat.', 'skor' => 3, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK016', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengambil keputusan yang paling aman untuk menghindari risiko.', 'skor' => 1, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK016', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Menunda keputusan sampai mendapatkan lebih banyak informasi atau kejelasan.', 'skor' => 0, 'target_kompetensi' => 'SE', 'aktif' => 1],

            // TK017: Ketika menghadapi masalah besar, apa langkah pertama yang kamu lakukan? (PS)
            ['id_soal' => 'TK017', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Coba lihat masalahnya dari berbagai sisi dan mulai buat rencana.', 'skor' => 4, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK017', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Pecah masalahnya jadi bagian kecil, biar lebih mudah ditangani.', 'skor' => 3, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK017', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya perlu waktu untuk menganalisis permasalahan', 'skor' => 2, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK017', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Menunda-nunda menghadapi masalah', 'skor' => 1, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK017', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Lihat dulu apakah masalah ini bisa hilang dengan sendirinya.', 'skor' => 0, 'target_kompetensi' => 'PS', 'aktif' => 1],

            // TK018: Jika solusi yang kamu pilih ternyata memiliki efek samping yang tidak terduga, bagaimana kamu menanganinya? (PS)
            ['id_soal' => 'TK018', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Segera menilai dampak sampingan dan menyesuaikan solusi untuk meminimalkan efeknya.', 'skor' => 4, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK018', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Mengidentifikasi masalah tersebut dan mencari cara untuk mengatasinya dengan cepat.', 'skor' => 3, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK018', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Memberikan penjelasan kepada pihak yang terlibat dan mencoba memperbaikinya.', 'skor' => 2, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK018', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengikuti prosedur untuk menangani masalah tersebut dan mencari solusi secara bertahap.', 'skor' => 1, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK018', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mengabaikan efek samping dan melanjutkan dengan solusi yang sudah diterapkan.', 'skor' => 0, 'target_kompetensi' => 'PS', 'aktif' => 1],

            // TK019: Jika kamu menemukan masalah yang belum pernah kamu hadapi, apa yang pertama kali kamu lakukan? (PS)
            ['id_soal' => 'TK019', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Cari tahu lebih banyak dulu, cari solusi yang belum terpikirkan.', 'skor' => 3, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK019', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Gunakan pendekatan yang pernah berhasil sebelumnya, mungkin bisa diterapkan.', 'skor' => 4, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK019', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Tanyakan ke teman atau rekan kerja yang mungkin punya pengalaman.', 'skor' => 2, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK019', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Ambil langkah pertama yang paling jelas dan lihat hasilnya.', 'skor' => 1, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK019', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Tunggu dulu, siapa tahu masalahnya akan selesai dengan sendirinya.', 'skor' => 0, 'target_kompetensi' => 'PS', 'aktif' => 1],

            // TK020: Jika kamu tahu ada rekan kerja yang tidak jujur dalam pekerjaannya, apa yang kamu lakukan? (PE)
            ['id_soal' => 'TK020', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Bicara dengan rekan tersebut secara pribadi untuk menyarankan cara yang lebih baik.', 'skor' => 3, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK020', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Jika perlu, beri tahu atasan agar masalah bisa ditangani dengan tepat.', 'skor' => 2, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK020', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Diam saja dan berharap masalahnya cepat selesai sendiri.', 'skor' => 0, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK020', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Menghindar dan tidak terlalu ikut campur, biar mereka urus masalahnya sendiri.', 'skor' => 1, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK020', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Bergabung dengan mereka, karena semua orang juga melakukan hal serupa.', 'skor' => 0, 'target_kompetensi' => 'PE', 'aktif' => 1],

            // TK021: Jika kamu terjebak dalam situasi di mana kamu harus memilih antara keuntungan pribadi dan kepentingan tim, apa yang kamu pilih? (PE)
            ['id_soal' => 'TK021', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Utamakan kepentingan tim, karena keberhasilan tim adalah keberhasilan bersama.', 'skor' => 4, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK021', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Pilih opsi yang memberi manfaat baik bagi dirimu maupun tim.', 'skor' => 3, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK021', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Tentukan pilihan yang paling menguntungkan bagi dirimu, karena itu akan memotivasi kerja lebih baik.', 'skor' => 0, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK021', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Utamakan dirimu, karena setiap orang harus memikirkan dirinya sendiri terlebih dahulu.', 'skor' => 1, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK021', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Pilih keuntungan pribadi, karena tak ada yang tahu  keputusan tersebut.', 'skor' => 1, 'target_kompetensi' => 'PE', 'aktif' => 1],

            // TK022: Jika kamu diberi kesempatan untuk mengambil kredit atas pekerjaan orang lain, apa yang akan kamu lakukan? (PE)
            ['id_soal' => 'TK022', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Tetap jujur dan memberikan penghargaan pada orang yang seharusnya mendapat kredit tersebut.', 'skor' => 4, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK022', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Ambil kredit untuk sebagian pekerjaan dan beri pengakuan pada orang lain untuk bagian lainnya.', 'skor' => 3, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK022', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Mengikuti saja apa yang dikreditkan untuk kita', 'skor' => 2, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK022', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya tidak akan mengambil kredit itu meskipun saya juga memiliki jasa didalamnya', 'skor' => 1, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK022', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya akan ambil kredit karena saya memberikan masukan dan solusi yang sangat berguna dibandingkan yang melaksankannya', 'skor' => 0, 'target_kompetensi' => 'PE', 'aktif' => 1],

            // TK023: Seberapa sering kamu menggunakan perangkat lunak atau alat tertentu yang relevan dengan pekerjaanmu? (GH)
            ['id_soal' => 'TK023', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Setiap hari, dan saya sangat menguasainya.', 'skor' => 4, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK023', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Cukup sering, saya merasa cukup nyaman menggunakannya.', 'skor' => 3, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK023', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Kadang-kadang, tapi saya masih perlu belajar lebih banyak.', 'skor' => 2, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK023', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Jarang, saya lebih memilih menggunakan cara manual atau tradisional.', 'skor' => 1, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK023', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Tidak pernah, saya tidak membutuhkan perangkat lunak tersebut.', 'skor' => 0, 'target_kompetensi' => 'GH', 'aktif' => 1],

            // TK024: Seberapa yakin kamu dengan kemampuan teknismu menggunakan perangkat lunak atau alat kerja terkait pekerjaanmu? (GH)
            ['id_soal' => 'TK024', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Sangat yakin, saya bisa menggunakannya dengan sangat baik dan efisien.', 'skor' => 4, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK024', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Cukup yakin, saya tahu cara menggunakannya meskipun masih ada hal-hal yang perlu dipelajari.', 'skor' => 3, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK024', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Ragu-ragu, saya tahu dasar-dasarnya tapi masih sering bingung dengan fitur lanjutan.', 'skor' => 2, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK024', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Tidak yakin, saya masih merasa kesulitan dengan penggunaan alat ini.', 'skor' => 1, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK024', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Tidak tahu sama sekali, saya belum pernah menggunakannya.', 'skor' => 0, 'target_kompetensi' => 'GH', 'aktif' => 1],

            // TK025: Jika kamu diminta untuk memberikan pelatihan tentang perangkat lunak yang kamu kuasai kepada rekan kerja, bagaimana kamu melakukannya? (GH)
            ['id_soal' => 'TK025', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Tidak merasa cukup percaya diri untuk memberikan pelatihan', 'skor' => 0, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK025', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menunjukkan fitur dasar dan membiarkan rekan kerja belajar lebih lanjut sendiri.', 'skor' => 1, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK025', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya buat materi pelatihan, tapi saya baca saja materinya.', 'skor' => 2, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK025', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya berikan contoh-contoh kasus yang sering ditemui', 'skor' => 3, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK025', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya menyesuaikan materi dengan kebutuhan dari rekan kerja saja', 'skor' => 4, 'target_kompetensi' => 'GH', 'aktif' => 1],

            // TK026: Kalau tugas lagi banyak dan deadline mepet, gimana biasanya kamu atur tugas-tugas itu? (SM)
            ['id_soal' => 'TK026', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Buat daftar prioritas dan urutkan mana yang paling penting', 'skor' => 4, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK026', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Selesaikan tugas-tugas yang lebih mudah terlebih dahulu untuk mengurangi beban', 'skor' => 3, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK026', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Kerjakan sesuai kondisi dan suasana hati saat itu', 'skor' => 1, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK026', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Diskusikan dengan rekan untuk menentukan prioritas yang sebaiknya diselesaikan lebih dulu', 'skor' => 2, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK026', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mengerjakan mendekati deadline itu efektif', 'skor' => 0, 'target_kompetensi' => 'SM', 'aktif' => 1],

            // TK027: Bagaimana cara kamu menkontrol diri dari pekerjaan yang sangat banyak dengan beban kerja yang juga berat? (SM)
            ['id_soal' => 'TK027', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya akan merasa burnout dan mengambil jeda sejenak untuk membreakdown strategi penyelesaian', 'skor' => 4, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK027', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya akan merasa sangat terbebani dengan pekerjaan yang sangat banyak, namun selalu saya usahakan selesai', 'skor' => 3, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK027', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya akan merasa pusing sehingga saya mengambil waktu sejak sebelum melanjutkan pekerjaan', 'skor' => 2, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK027', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya akan merasa strees, namun saya akan megerjakan sampai akhir', 'skor' => 1, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK027', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya akan merasa burnout produktivitas akan turun, oleh karena itu saya perlu waktu yang lebih lama', 'skor' => 0, 'target_kompetensi' => 'SM', 'aktif' => 1],

            // TK028: Gimana caramu memastikan tugas selesai tepat waktu? (SM)
            ['id_soal' => 'TK028', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Metode SKS (SIstem kebut semalam)', 'skor' => 1, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK028', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Mulai bekerja ketika suasana hati sedang mendukung', 'skor' => 0, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK028', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Fokus penuh pada pekerjaan dan jauhkan diri dari hal-hal yang mengganggu', 'skor' => 3, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK028', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mulai kerjakan langsung tanpa menunda-nunda', 'skor' => 2, 'target_kompetensi' => 'SM', 'aktif' => 1],
            ['id_soal' => 'TK028', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Buat daftar tugas harian (to-do list) agar semua langkah terencana', 'skor' => 4, 'target_kompetensi' => 'SM', 'aktif' => 1],

            // TK029: Kalau kamu dapat informasi dari beberapa sumber yang beda-beda, biasanya gimana kamu milih yang tepat? (TS)
            ['id_soal' => 'TK029', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya akan bertanya ke rekan saya mengenai opininya terhadap informasi itu', 'skor' => 0, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK029', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya akan megambil informasi yang paling awal dan akhir saja karena abstraksi dan kesimpulan ada di awal dan diakhir', 'skor' => 1, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK029', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya akan memilih informasi yang paling panjang isinya', 'skor' => 2, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK029', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya akan membaca semua informasi dan mengambil informasi yang berulang', 'skor' => 3, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK029', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya akan melihat sumber dari informasi saya dapatkan', 'skor' => 4, 'target_kompetensi' => 'TS', 'aktif' => 1],

            // TK030: Kalau ada masalah baru, apa langkah pertama yang kamu ambil? (TS)
            ['id_soal' => 'TK030', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Analisis akar masalahnya terlebih dahulu, lalu buat rencana penyelesaian', 'skor' => 4, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK030', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Coba solusi pertama yang terpikirkan untuk melihat hasilnya', 'skor' => 3, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK030', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Konsultasikan dengan rekan yang sudah berpengalaman dalam hal ini', 'skor' => 2, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK030', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Cek referensi atau solusi serupa yang ada di internet', 'skor' => 1, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK030', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Observasi situasi terlebih dahulu sebelum mengambil keputusan', 'skor' => 0, 'target_kompetensi' => 'TS', 'aktif' => 1],

            // TK031: Kalau dapat tugas yang susah, gimana caramu nentuin langkah yang harus diambil? (TS)
            ['id_soal' => 'TK031', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Berkonsultasi terlebih dahulu dengan rekan atau atasan yang lebih berpengalaman', 'skor' => 4, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK031', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Membagi tugas mulai dari yang paling mudah dan bagian yang aku pahami sampai dengan bagian yang sulit dan tidak aku pahami', 'skor' => 3, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK031', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Mencari referensi maupuan informasi sebelum mengerjakan tugas, jika refensi belum dapat maka tugas tidak bisa dikerjaka', 'skor' => 2, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK031', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengerjakan kecil-kecil yang masih bisa dipahami terlebih dahulu ranpa ada perencanaan', 'skor' => 1, 'target_kompetensi' => 'TS', 'aktif' => 1],
            ['id_soal' => 'TK031', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'menunggu pencerahan untuk mengerjakan tugas itu', 'skor' => 0, 'target_kompetensi' => 'TS', 'aktif' => 1],

            // TK032: Gimana cara kamu supaya ide kamu dipahami sama tim? (CIA)
            ['id_soal' => 'TK032', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Jelaskan dengan apa adanya secara spontan', 'skor' => 0, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK032', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya tidak peduli apakah ide saya diterima atau tidak', 'skor' => 1, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK032', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya hanya menjelaskan ide saya kepada orang yang saya anggap penting', 'skor' => 2, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK032', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Gunakan contoh konkret untuk memperjelas ide', 'skor' => 3, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK032', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya antusias menjelaskan ide saya agar tim juga tertarik', 'skor' => 4, 'target_kompetensi' => 'CIA', 'aktif' => 1],

            // TK033: Kalau ada pendapat yang beda dalam tim, gimana biasanya kamu menyikapi? (CIA)
            ['id_soal' => 'TK033', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'membiarkan perbedaan pendapat karena itu adalah hak mereka', 'skor' => 2, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK033', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'mendengarkan pendapat yang berbeda dan mencoba memajami sudut pandangnya', 'skor' => 3, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK033', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya akan berpihak kepada orang yang saya kenal dekat', 'skor' => 0, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK033', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya akan mengikuti suara mayoritas saja', 'skor' => 1, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK033', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Saya akan menjadi fasilitastor menengahi perbedaan pendapat untuk mencapai kesepakatan', 'skor' => 4, 'target_kompetensi' => 'CIA', 'aktif' => 1],

            // TK034: Kalau temen kerja lagi down, apa yang kamu lakuin buat bantu? (CIA)
            ['id_soal' => 'TK034', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Dengarkan dan berikan semangat agar mereka lebih optimis', 'skor' => 4, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK034', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Biarkan mereka punya waktu sendiri sampai siap kembali', 'skor' => 3, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK034', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Ajak ngobrol ringan untuk menciptakan suasana santai', 'skor' => 2, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK034', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Ajak mereka rehat sejenak atau jalan-jalan supaya lebih rileks', 'skor' => 1, 'target_kompetensi' => 'CIA', 'aktif' => 1],
            ['id_soal' => 'TK034', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Bantu meringankan beban tugasnya untuk sementara', 'skor' => 0, 'target_kompetensi' => 'CIA', 'aktif' => 1],

            // TK035: Kalau ada yang nggak aktif di tim, gimana cara kamu ngajak dia supaya lebih aktif? (WWO)
            ['id_soal' => 'TK035', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Berikan dukungan dan motivasi untuk ikut aktif', 'skor' => 3, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK035', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Tanyakan alasannya agar bisa memahami kendalanya', 'skor' => 2, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK035', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Tunggu saja, berharap nanti mereka akan ikut sendiri', 'skor' => 0, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK035', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Libatkan mereka dalam tugas-tugas kecil terlebih dahulu', 'skor' => 4, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK035', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Bicara secara baik-baik agar mereka merasa nyaman berpartisipasi', 'skor' => 1, 'target_kompetensi' => 'WWO', 'aktif' => 1],

            // TK036: Kalau tim punya pendapat yang beda-beda, gimana kamu biar semuanya bisa sepakat? (WWO)
            ['id_soal' => 'TK036', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Karena keduanya sama-sama keras kepala maka akan saya biarkan saja, selama tidak ada kekerasan yang terjadi', 'skor' => 0, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK036', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Saya akan mengikuti suara mayoritas', 'skor' => 1, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK036', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya akan memberikan pendapat saya pribadi', 'skor' => 2, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK036', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Saya akan mencoba mendengar pendapat semua orang', 'skor' => 3, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK036', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Diskusikan cara untuk mencapai kompromi agar semua merasa nyaman', 'skor' => 4, 'target_kompetensi' => 'WWO', 'aktif' => 1],

            // TK037: Gimana cara kamu biar bisa kerjasama dengan orang yang punya cara kerja beda? (WWO)
            ['id_soal' => 'TK037', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Menyesuaikan diri dengan cara kerja mereka', 'skor' => 1, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK037', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Tetap dengan pendekatan pribadi', 'skor' => 0, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK037', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Cari titik tengah agar keduanya nyaman', 'skor' => 2, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK037', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Pelajari cara mereka untuk lebih mudah beradaptasi', 'skor' => 3, 'target_kompetensi' => 'WWO', 'aktif' => 1],
            ['id_soal' => 'TK037', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Diskusikan cara kerja masing-masing untuk saling memahami', 'skor' => 4, 'target_kompetensi' => 'WWO', 'aktif' => 1],

            // TK038: Gimana caramu nunjukin kalau kamu punya semangat buat berkembang? (CA)
            ['id_soal' => 'TK038', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mengikuti pelatihan tambahan yang bisa meningkatkan keterampilan', 'skor' => 3, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK038', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menunggu kesempatan baru datang dan siap mengambil peluang', 'skor' => 0, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK038', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Belajar mandiri dan mengembangkan diri saat ada waktu luang', 'skor' => 2, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK038', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengikuti aktivitas yang diambil oleh rekan kerja', 'skor' => 1, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK038', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mencari tugas-tugas baru untuk mendapatkan lebih banyak pengalaman', 'skor' => 4, 'target_kompetensi' => 'CA', 'aktif' => 1],

            // TK039: Kalau ada hambatan di kerjaan, apa yang biasanya kamu lakuin? (CA)
            ['id_soal' => 'TK039', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mencari cara sendiri untuk menyelesaikan masalah tersebut', 'skor' => 1, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK039', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Tanya rekan atau atasan yang mungkin lebih berpengalaman', 'skor' => 3, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK039', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Biarkan saja jika merasa masalahnya terlalu sulit', 'skor' => 0, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK039', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mencari solusi atau referensi dari sumber online', 'skor' => 2, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK039', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Menghadapinya dan jika tidak mengetahui caranya saya akan bertanya', 'skor' => 4, 'target_kompetensi' => 'CA', 'aktif' => 1],

            // TK040: Kalau dikasih tugas baru, gimana sikap kamu buat belajar hal-hal baru? (CA)
            ['id_soal' => 'TK040', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mengambil tugas itu dengan percaya diri dan yakin bisa menyelesaikan', 'skor' => 4, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK040', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'menerima saja meskipun tidak terlalu antusias', 'skor' => 1, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK040', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Belajar seadanya yang penting tugas selesai', 'skor' => 0, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK040', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Pelajari secara bertahap agar lebih paham', 'skor' => 2, 'target_kompetensi' => 'CA', 'aktif' => 1],
            ['id_soal' => 'TK040', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mencari rekan kerja yang memiliki tugas yang sama, biar bisa bekerjasama', 'skor' => 3, 'target_kompetensi' => 'CA', 'aktif' => 1],

            // TK041: Kalau nggak ada yang ambil inisiatif di tim, gimana cara kamu biasanya bertindak? (L)
            ['id_soal' => 'TK041', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Langsung mengambil inisiatif sendiri', 'skor' => 2, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK041', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Menunggu sampai ada yang mulai', 'skor' => 0, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK041', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Memberikan saran agar tim bisa mulai bergerak', 'skor' => 3, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK041', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengajak diskusi tim untuk mencari solusi bersama', 'skor' => 4, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK041', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Menunggu arahan dari atasan', 'skor' => 1, 'target_kompetensi' => 'L', 'aktif' => 1],

            // TK042: Kalau ada anggota tim yang kurang semangat, apa yang kamu lakuin biar dia termotivasi? (L)
            ['id_soal' => 'TK042', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Memberikan semangat dan menjadi contoh yang baik', 'skor' => 4, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK042', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Memberikan tugas agar mereka lebih sibuk dan fokus', 'skor' => 2, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK042', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Membiarkan mereka untuk menemukan semangatnya sendiri', 'skor' => 0, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK042', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Menengurnya dan mengingatkannya', 'skor' => 1, 'target_kompetensi' => 'L', 'aktif' => 1],
            ['id_soal' => 'TK042', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Membantu mencari solusi untuk meningkatkan motivasi mereka', 'skor' => 3, 'target_kompetensi' => 'L', 'aktif' => 1],

            // TK043: Gimana perasaan kamu kalau dikasih tugas baru yang menantang? (SE)
            ['id_soal' => 'TK043', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Tidak tertarik karena bukan jobdesk utama saya', 'skor' => 0, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK043', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Mengomel karena tugas tambahan yang tidak sesuai dengan keahlian', 'skor' => 1, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK043', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Strees dan Overthinking duluan takut ngga bisa ngerjain', 'skor' => 2, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK043', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Menerima kemudian mencoba dan belajar', 'skor' => 3, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK043', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Merasa lebih percaya diri untuk mengambil tantangan', 'skor' => 4, 'target_kompetensi' => 'SE', 'aktif' => 1],

            // TK044: Kalau dapat kritik dari atasan, gimana cara kamu menghadapinya? (SE)
            ['id_soal' => 'TK044', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Mencari pembenaran dari kritik yang diberikan untuk membenarkan diri', 'skor' => 0, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK044', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Diam saja menerima kritikan', 'skor' => 1, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK044', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Saya merasa down dan tidak bersemangat bekerja', 'skor' => 2, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK044', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Berterima kasih atas masukan yang diberikan dan berusaha memperbaikinya', 'skor' => 3, 'target_kompetensi' => 'SE', 'aktif' => 1],
            ['id_soal' => 'TK044', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Menerima kritik dan evaluasi diri mencari tau apa yang harus ditingkatkan', 'skor' => 4, 'target_kompetensi' => 'SE', 'aktif' => 1],

            // TK045: Kalau kamu nemuin masalah baru di kerjaan, biasanya apa langkah pertama kamu? (PS)
            ['id_soal' => 'TK045', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Menganalisis penyebab masalah sebelum membuat keputusan', 'skor' => 4, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK045', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Mencoba solusi pertama yang terlintas dalam pikiran', 'skor' => 1, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK045', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Bertanya kepada rekan yang lebih berpengalaman', 'skor' => 3, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK045', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mencari referensi solusi di internet', 'skor' => 2, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK045', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mengamati lebih lanjut dan menunggu sebelum mengambil langkah', 'skor' => 0, 'target_kompetensi' => 'PS', 'aktif' => 1],

            // TK046: Kalau solusi pertama gagal, gimana cara kamu mencari alternatif lain? (PS)
            ['id_soal' => 'TK046', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Segera mencari solusi lain yang mungkin lebih cocok', 'skor' => 1, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK046', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Bertanya kepada rekan atau atasan untuk saran lebih lanjut', 'skor' => 4, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK046', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Mengabaikan dan melanjutkan tugas lain', 'skor' => 0, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK046', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mengevaluasi kesalahan dari solusi pertama untuk memperbaiki', 'skor' => 3, 'target_kompetensi' => 'PS', 'aktif' => 1],
            ['id_soal' => 'TK046', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Mempelajari pendekatan lain yang belum dicoba', 'skor' => 2, 'target_kompetensi' => 'PS', 'aktif' => 1],

            // TK047: Kalau ada rekan yang melakukan pelanggaran aturan, gimana cara kamu bertindak? (PE)
            ['id_soal' => 'TK047', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Melaporkan kepada pihak yang bertugas karena dilakukan secara sadara diri', 'skor' => 3, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK047', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Memberikan nasihat pribadi kepada rekan tersebut dan menindak keras jika terjadi kembali', 'skor' => 4, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK047', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Mengabaikan karena merasa itu bukan urusan', 'skor' => 0, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK047', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Memberikan peringatan secara anonim dan melaporkan secara anonim', 'skor' => 2, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK047', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Menyebarkan pelanggaran itu ke rekan lainnya', 'skor' => 1, 'target_kompetensi' => 'PE', 'aktif' => 1],

            // TK048: Saat dikasih tugas yang susah, gimana kamu memastikan tetap bertindak jujur dan bertanggung jawab? (PE)
            ['id_soal' => 'TK048', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Memastikan bahwa semua data dan pekerjaan sesuai dengan standar yang ditetapkan', 'skor' => 3, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK048', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'Mengusahakan yang terbaik semampu mungkin dalam menyelesaikan tugas', 'skor' => 4, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK048', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Mengabaikan detail kecil agar pekerjaan cepat selesai', 'skor' => 0, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK048', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Bertanya jika ada bagian yang kurang jelas', 'skor' => 1, 'target_kompetensi' => 'PE', 'aktif' => 1],
            ['id_soal' => 'TK048', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Merevisi hasil jika ditemukan kesalahan di akhir pekerjaan', 'skor' => 2, 'target_kompetensi' => 'PE', 'aktif' => 1],

            // TK049: Kalau ada teknologi atau alat baru yang perlu dipelajari, gimana biasanya kamu menyikapinya? (GH)
            ['id_soal' => 'TK049', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Menunggu arahan untuk menggunakannya', 'skor' => 0, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK049', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'jika ada keperluan baru mengakses dan mempelajarinya', 'skor' => 1, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK049', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'Coba-coba sendiri jika ada waktu', 'skor' => 2, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK049', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'Mencari tutorial atau materi terkait secara online', 'skor' => 3, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK049', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'Latihan secara bertahap untuk memahami lebih baik', 'skor' => 4, 'target_kompetensi' => 'GH', 'aktif' => 1],

            // TK050: Kalau dikasih tugas teknis yang kamu belum kuasai, apa yang kamu lakukan? (GH)
            ['id_soal' => 'TK050', 'huruf_pilihan' => 'a', 'teks_pilihan' => 'Saya akan menyampaikan bahwa saya tidak menguasainya', 'skor' => 0, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK050', 'huruf_pilihan' => 'b', 'teks_pilihan' => 'saya mencari rekan yang memahami dan meminta tolong padanya untuk menggantikan', 'skor' => 1, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK050', 'huruf_pilihan' => 'c', 'teks_pilihan' => 'saya akan mencoba-coba jika mudah saya suka, namun jika sulit saya akan menunggu diajarkan', 'skor' => 2, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK050', 'huruf_pilihan' => 'd', 'teks_pilihan' => 'saya akan meminta tolong kepada teman saya yang menguasai untuk membantu saya', 'skor' => 3, 'target_kompetensi' => 'GH', 'aktif' => 1],
            ['id_soal' => 'TK050', 'huruf_pilihan' => 'e', 'teks_pilihan' => 'saya akan meminta rekan/atasan mengajari saya dan mencari sumber pembelajaran', 'skor' => 4, 'target_kompetensi' => 'GH', 'aktif' => 1],
        ];

        $counter = 1;
        $waktuSekarang = now()->toDateTimeString(); // Format waktu string yang aman untuk DB::table

        foreach ($options as $key => $option) {
            $options[$key]['id'] = 'PILT' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $options[$key]['created_at'] = $waktuSekarang;
            $options[$key]['updated_at'] = $waktuSekarang;
            $counter++;
        }

        // 2. PERINTAH INSERT (Harus diletakkan di PALING BAWAH)
        // Gunakan array chunkSize agar tidak memberatkan memori jika datanya banyak
        foreach (array_chunk($options, 100) as $chunk) {
            DB::table('pilihan_tk')->insert($chunk);
        }
    }
}
