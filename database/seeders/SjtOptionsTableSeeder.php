<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SjtOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('sjt_options')->delete();

        DB::table('sjt_options')->insert(array (
            // SJ101: Bagaimana cara kamu mengatur waktu untuk tugas yang butuh fokus tinggi? (SM)
            0 => array (
                'id' => 'SJO001', 'question_id' => 'SJ101', 'option_letter' => 'a', 'option_text' => 'Bikin jadwal dengan jeda istirahat teratur biar fokus nggak cepat habis.',
                'score' => 4, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            1 => array (
                'id' => 'SJO002', 'question_id' => 'SJ101', 'option_letter' => 'b', 'option_text' => 'Kerjain terus tanpa jeda biar cepat selesai, istirahat belakangan.',
                'score' => 3, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            2 => array (
                'id' => 'SJO003', 'question_id' => 'SJ101', 'option_letter' => 'c', 'option_text' => 'Mengerjakan semuanya apa adanya biar semua selesai.',
                'score' => 2, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            3 => array (
                'id' => 'SJO004', 'question_id' => 'SJ101', 'option_letter' => 'd', 'option_text' => 'Kerjain sesuai mood, asal nggak terlalu banyak gangguan.',
                'score' => 1, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            4 => array (
                'id' => 'SJO005', 'question_id' => 'SJ101', 'option_letter' => 'e', 'option_text' => 'Mulai kerja kalau benar-benar mood aja, nggak pengen buru-buru.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ102: Kalau ada situasi bikin emosi di tempat kerja, gimana caramu merespons? (SM)
            5 => array (
                'id' => 'SJO006', 'question_id' => 'SJ102', 'option_letter' => 'a', 'option_text' => 'Menyalurkan emosi untuk melepaskan kekesalan', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            6 => array (
                'id' => 'SJO007', 'question_id' => 'SJ102', 'option_letter' => 'b', 'option_text' => 'Meninggalkan segera tempat agar tidak terpancing emosi', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            7 => array (
                'id' => 'SJO008', 'question_id' => 'SJ102', 'option_letter' => 'c', 'option_text' => 'Diam saja namun memendam emosi', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            8 => array (
                'id' => 'SJO009', 'question_id' => 'SJ102', 'option_letter' => 'd', 'option_text' => 'Alihkan perhatian ke hal lain dulu, baru kembali menghadapinya', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            9 => array (
                'id' => 'SJO010', 'question_id' => 'SJ102', 'option_letter' => 'e', 'option_text' => 'Mengkontrol emosi dalam diri dan menghadapi dengan tenang', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ103: Saat menghadapi masalah yang belum pernah kamu temui sebelumnya, gimana caramu mencari solusinya (TS)
            10 => array (
                'id' => 'SJO011', 'question_id' => 'SJ103', 'option_letter' => 'a', 'option_text' => 'Saya menunggu bantuan dari orang lain', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            11 => array (
                'id' => 'SJO012', 'question_id' => 'SJ103', 'option_letter' => 'b', 'option_text' => 'Saya akan coba menyelesaikan semaksimal mugkin yang bisa saya lakukan', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            12 => array (
                'id' => 'SJO013', 'question_id' => 'SJ103', 'option_letter' => 'c', 'option_text' => 'Saya akan mencari informasi umum terlebih dahulu mengenai permasalahan', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            13 => array (
                'id' => 'SJO014', 'question_id' => 'SJ103', 'option_letter' => 'd', 'option_text' => 'Saya mencari solusi dengan riset lebih dalam atau diskusi dengan orang yang lebih paham.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            14 => array (
                'id' => 'SJO015', 'question_id' => 'SJ103', 'option_letter' => 'e', 'option_text' => 'Saya menganalisis masalah dengan riset serta diskusi dan mencoba beberapa pendekatan sampai menemukan solusi terbaik', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ104: Bagaimana kamu mengevaluasi keputusan yang sudah diambil? (TS)
            15 => array (
                'id' => 'SJO016', 'question_id' => 'SJ104', 'option_letter' => 'a', 'option_text' => 'Menganalisis hasil dan mencocokkannya dengan tujuan awal, lalu membuat opsi perbaikan', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            16 => array (
                'id' => 'SJO017', 'question_id' => 'SJ104', 'option_letter' => 'b', 'option_text' => 'Saya akan menanyakan dampak dari keputusan saya terhadap tim', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            17 => array (
                'id' => 'SJO018', 'question_id' => 'SJ104', 'option_letter' => 'c', 'option_text' => 'Saya akan mencoba melihat hasil dari keputusan saya dan memikirkan apa yang bisa diperbaiki', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            18 => array (
                'id' => 'SJO019', 'question_id' => 'SJ104', 'option_letter' => 'd', 'option_text' => 'Saya selalu berpikiran berlebihan mengenai keputusan yang sudah saya ambil, saya masih ragu apakah itu benar, kurang tepat, atau salah', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            19 => array (
                'id' => 'SJO020', 'question_id' => 'SJ104', 'option_letter' => 'e', 'option_text' => 'Saya sudah yakin dengan keputusan yang sudah saya ambil', // Opsi Text Diperbarui (f menjadi e)
                'score' => 0, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ105: Saat kamu ingin menyampaikan ide baru kepada tim, apa yang biasanya kamu lakukan? (CIA)
            20 => array (
                'id' => 'SJO021', 'question_id' => 'SJ105', 'option_letter' => 'a', 'option_text' => 'saya ragu terhadap ide yang saya miliki jadi lebih memilih diam saja', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            21 => array (
                'id' => 'SJO022', 'question_id' => 'SJ105', 'option_letter' => 'b', 'option_text' => 'Saya menyampaikan apa yang terlintas di benak saya', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            22 => array (
                'id' => 'SJO023', 'question_id' => 'SJ105', 'option_letter' => 'c', 'option_text' => 'Saya berusaha menyakinkan tim bahwa ide saya adalah yang terbaik', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            23 => array (
                'id' => 'SJO024', 'question_id' => 'SJ105', 'option_letter' => 'd', 'option_text' => 'Mengajak tim berdiskusi untuk mendapatkan masukan tentang ide saya', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            24 => array (
                'id' => 'SJO025', 'question_id' => 'SJ105', 'option_letter' => 'e', 'option_text' => 'Menyampaikan ide secara santai sambil mendengar pendapat dari tim.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ106: Saat ada diskusi kelompok, bagaimana kamu memastikan setiap orang mendapat kesempatan bicara? (CIA)
            25 => array (
                'id' => 'SJO026', 'question_id' => 'SJ106', 'option_letter' => 'a', 'option_text' => 'Mengajukan pertanyaan agar rekan kerja merasa didengarkan dan terlibat.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            26 => array (
                'id' => 'SJO027', 'question_id' => 'SJ106', 'option_letter' => 'b', 'option_text' => 'Memberikan ruang bagi rekan yang ingin berbicara, dan tidak mendominasi percakapan.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            27 => array (
                'id' => 'SJO028', 'question_id' => 'SJ106', 'option_letter' => 'c', 'option_text' => 'Menyimak dengan aktif dan mengundang pendapat dari mereka yang lebih pendiam.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            28 => array (
                'id' => 'SJO029', 'question_id' => 'SJ106', 'option_letter' => 'd', 'option_text' => 'Memastikan suasana diskusi nyaman agar semua orang merasa bebas berbicara.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            29 => array (
                'id' => 'SJO030', 'question_id' => 'SJ106', 'option_letter' => 'e', 'option_text' => 'Memberikan tanggapan positif setiap kali mereka menyampaikan ide.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ107: Jika kamu bekerja dengan rekan yang memiliki cara atau pandangan berbeda, bagaimana kamu menyikapinya? (WWO)
            30 => array (
                'id' => 'SJO031', 'question_id' => 'SJ107', 'option_letter' => 'a', 'option_text' => 'Mencari kesempatan untuk belajar dari sudut pandang atau pendekatan mereka.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            31 => array (
                'id' => 'SJO032', 'question_id' => 'SJ107', 'option_letter' => 'b', 'option_text' => 'Menghormati cara kerja mereka dan beradaptasi jika perlu untuk mencapai tujuan tim.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            32 => array (
                'id' => 'SJO033', 'question_id' => 'SJ107', 'option_letter' => 'c', 'option_text' => 'Menyampaikan ide atau cara yang kamu yakini secara terbuka dan sopan.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            33 => array (
                'id' => 'SJO034', 'question_id' => 'SJ107', 'option_letter' => 'd', 'option_text' => 'Berdiskusi dengan rekan tersebut untuk mencari cara kerja yang selaras.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            34 => array (
                'id' => 'SJO035', 'question_id' => 'SJ107', 'option_letter' => 'e', 'option_text' => 'Fokus pada hasil akhir dan fleksibel dengan pendekatan mereka.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ108: Ketika tugas dibagi dalam tim, apa yang biasanya kamu lakukan? (WWO)
            35 => array (
                'id' => 'SJO036', 'question_id' => 'SJ108', 'option_letter' => 'a', 'option_text' => 'Saya akan menunggu yang lain bergerak baru saya mengikuti', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            36 => array (
                'id' => 'SJO037', 'question_id' => 'SJ108', 'option_letter' => 'b', 'option_text' => 'Saya lebih suka mengerjakan sendiri semuanya', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            37 => array (
                'id' => 'SJO038', 'question_id' => 'SJ108', 'option_letter' => 'c', 'option_text' => 'saya fokus menyelesaikan bagian tugas yang diberikan kepada saya', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            38 => array (
                'id' => 'SJO039', 'question_id' => 'SJ108', 'option_letter' => 'd', 'option_text' => 'Menanyakan apakah ada yang butuh bantuan dalam menyelesaikan tugas.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            39 => array (
                'id' => 'SJO040', 'question_id' => 'SJ108', 'option_letter' => 'e', 'option_text' => 'Saya akan mencari penyelesaikan tugas yang efisien dan memastikan tugas selesai tepat waktu.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ109: Bagaimana kamu menjaga motivasi dalam bekerja, terutama saat menghadapi tantangan? (CA)
            40 => array (
                'id' => 'SJO041', 'question_id' => 'SJ109', 'option_letter' => 'a', 'option_text' => 'Mengingat tujuan jangka panjang dan dampak positif dari pekerjaan ini.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            41 => array (
                'id' => 'SJO042', 'question_id' => 'SJ109', 'option_letter' => 'b', 'option_text' => 'Mengambil jeda sejenak untuk refleksi, lalu kembali dengan energi baru.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            42 => array (
                'id' => 'SJO043', 'question_id' => 'SJ109', 'option_letter' => 'c', 'option_text' => 'Membuat daftar tujuan harian untuk menjaga semangat bekerja.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            43 => array (
                'id' => 'SJO044', 'question_id' => 'SJ109', 'option_letter' => 'd', 'option_text' => 'Mencari inspirasi dari rekan kerja atau mentor untuk memotivasi diri.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            44 => array (
                'id' => 'SJO045', 'question_id' => 'SJ109', 'option_letter' => 'e', 'option_text' => 'Fokus pada aspek-aspek pekerjaan yang paling kamu nikmati.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ110: Saat kamu menghadapi kesulitan dalam pekerjaan, apa yang biasanya kamu lakukan? (CA)
            45 => array (
                'id' => 'SJO046', 'question_id' => 'SJ110', 'option_letter' => 'a', 'option_text' => 'Mencari solusi secara mandiri dan mencoba beberapa pendekatan berbeda.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            46 => array (
                'id' => 'SJO047', 'question_id' => 'SJ110', 'option_letter' => 'b', 'option_text' => 'Bertanya atau berdiskusi dengan rekan untuk menemukan jalan keluar bersama.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            47 => array (
                'id' => 'SJO048', 'question_id' => 'SJ110', 'option_letter' => 'c', 'option_text' => 'Melakukan riset tambahan untuk memahami masalah dengan lebih baik.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            48 => array (
                'id' => 'SJO049', 'question_id' => 'SJ110', 'option_letter' => 'd', 'option_text' => 'Memprioritaskan penyelesaian masalah dengan langkah-langkah kecil.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            49 => array (
                'id' => 'SJO050', 'question_id' => 'SJ110', 'option_letter' => 'e', 'option_text' => 'Mengambil waktu sejenak untuk menyusun strategi sebelum bertindak.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ111: Bagaimana kamu menjaga semangat dan motivasi dalam tim saat menghadapi tantangan? (L)
            50 => array (
                'id' => 'SJO051', 'question_id' => 'SJ111', 'option_letter' => 'a', 'option_text' => 'Menyemangati tim dengan mengingatkan tujuan bersama dan kontribusi tiap orang.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            51 => array (
                'id' => 'SJO052', 'question_id' => 'SJ111', 'option_letter' => 'b', 'option_text' => 'Menyediakan dukungan dengan berbicara positif dan memotivasi secara personal.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            52 => array (
                'id' => 'SJO053', 'question_id' => 'SJ111', 'option_letter' => 'c', 'option_text' => 'Mengajak tim untuk beristirahat sejenak dan kembali bekerja dengan energi baru.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            53 => array (
                'id' => 'SJO054', 'question_id' => 'SJ111', 'option_letter' => 'd', 'option_text' => 'Membangun lingkungan kerja yang ramah dan mendukung.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            54 => array (
                'id' => 'SJO055', 'question_id' => 'SJ111', 'option_letter' => 'e', 'option_text' => 'Menjadi contoh dengan menunjukkan sikap tenang dan percaya diri.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ112: Ketika tim membutuhkan seseorang untuk bertanggung jawab pada suatu proyek, bagaimana responsmu? (L)
            55 => array (
                'id' => 'SJO056', 'question_id' => 'SJ112', 'option_letter' => 'a', 'option_text' => 'Saya akan langsung siap dan bersemangat untuk mengambil tanggung jawab tersebut.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            56 => array (
                'id' => 'SJO057', 'question_id' => 'SJ112', 'option_letter' => 'b', 'option_text' => 'Menyusun strategi bersama dan musyawarah untuk menentukan siapa yang cocok untuk bertanggung jawab', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            57 => array (
                'id' => 'SJO058', 'question_id' => 'SJ112', 'option_letter' => 'c', 'option_text' => 'Saya akan bersedia membantu, tetapi perlu melihat dulu detail proyek dan beban kerja saya saat ini.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            58 => array (
                'id' => 'SJO059', 'question_id' => 'SJ112', 'option_letter' => 'd', 'option_text' => 'Mengamati kebutuhan proyek dan memberikan dukungan sesuai yang dibutuhkan.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            59 => array (
                'id' => 'SJO060', 'question_id' => 'SJ112', 'option_letter' => 'e', 'option_text' => 'Saya kurang tertarik untuk mengambil tanggung jawab proyek', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ113: Bagaimana kamu menginspirasi rekan kerja melalui tindakan dan sikap sehari-hari? (L)
            60 => array (
                'id' => 'SJO061', 'question_id' => 'SJ113', 'option_letter' => 'a', 'option_text' => 'Memberikan contoh dengan bekerja keras dan berfokus pada hasil yang berkualitas.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            61 => array (
                'id' => 'SJO062', 'question_id' => 'SJ113', 'option_letter' => 'b', 'option_text' => 'Menunjukkan empati dan kepedulian terhadap kesejahteraan tim.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            62 => array (
                'id' => 'SJO063', 'question_id' => 'SJ113', 'option_letter' => 'c', 'option_text' => 'Saya sering memberikan pujian dan apresiasi kepada rekan kerja atas prestasi yang mereka capai.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            63 => array (
                'id' => 'SJO064', 'question_id' => 'SJ113', 'option_letter' => 'd', 'option_text' => 'Saya hanya akan memberikan bantuan jika diminta oleh rekan kerja.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            64 => array (
                'id' => 'SJO065', 'question_id' => 'SJ113', 'option_letter' => 'e', 'option_text' => 'Saya merasa sulit untuk berinteraksi dengan orang lain dan lebih suka bekerja sendiri.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ114: Bagaimana kamu menunjukkan rasa percaya diri saat berinteraksi dengan rekan kerja baru? (SE)
            65 => array (
                'id' => 'SJO066', 'question_id' => 'SJ114', 'option_letter' => 'a', 'option_text' => 'Saya biasanya pasif dan lebih memilih menyendiri', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            66 => array (
                'id' => 'SJO067', 'question_id' => 'SJ114', 'option_letter' => 'b', 'option_text' => 'Saya biasanya diam saja dan lebih banyak mendengarkan', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            67 => array (
                'id' => 'SJO068', 'question_id' => 'SJ114', 'option_letter' => 'c', 'option_text' => 'Saya berusaha memulai percakapan, namun topiknya masih terbatas', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            68 => array (
                'id' => 'SJO069', 'question_id' => 'SJ114', 'option_letter' => 'd', 'option_text' => 'Saya aktif bertanya dan memberikan pendapat', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            69 => array (
                'id' => 'SJO070', 'question_id' => 'SJ114', 'option_letter' => 'e', 'option_text' => 'Saya selalu berusaha menjadi yang pertama menyapa dan memulai pembicaraan', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ115: Bagaimana kamu memperlakukan kegagalan atau kesalahan dalam pekerjaan? (SE)
            70 => array (
                'id' => 'SJO071', 'question_id' => 'SJ115', 'option_letter' => 'a', 'option_text' => 'Saya akan menyembunyikan kesalahan saya dan berharap tidak ada yang menyadarinya', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            71 => array (
                'id' => 'SJO072', 'question_id' => 'SJ115', 'option_letter' => 'b', 'option_text' => 'Saya akan sangat malu dengan kesalahan saya dan sulit untuk melupakannya', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            72 => array (
                'id' => 'SJO073', 'question_id' => 'SJ115', 'option_letter' => 'c', 'option_text' => 'Saya mengakui kesalahan saya, namun saya kurang suka jika orang lain menyebutnya dan mengkritik saya', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            73 => array (
                'id' => 'SJO074', 'question_id' => 'SJ115', 'option_letter' => 'd', 'option_text' => 'Saya akan meminta maaf atas kesalahan saya dan berusaha untuk memperbaikinya secepat mungkin.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            74 => array (
                'id' => 'SJO075', 'question_id' => 'SJ115', 'option_letter' => 'e', 'option_text' => 'Saya akan menganalisis kesalahan saya untuk memahami apa yang salah dan bagaimana mencegahnya terulang.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ116: Ketika menghadapi situasi baru yang memerlukan keputusan cepat, apa yang biasanya kamu lakukan? (SE)
            75 => array (
                'id' => 'SJO076', 'question_id' => 'SJ116', 'option_letter' => 'a', 'option_text' => 'Membuat keputusan berdasarkan keyakinan diri dan pengalaman yang relevan.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            76 => array (
                'id' => 'SJO077', 'question_id' => 'SJ116', 'option_letter' => 'b', 'option_text' => 'Menganalisis situasi dengan cepat dan mengambil langkah yang paling logis.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            77 => array (
                'id' => 'SJO078', 'question_id' => 'SJ116', 'option_letter' => 'c', 'option_text' => 'Berdiskusi dengan rekan atau atasan untuk memastikan keputusan yang diambil tepat.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            78 => array (
                'id' => 'SJO079', 'question_id' => 'SJ116', 'option_letter' => 'd', 'option_text' => 'Mengambil keputusan yang paling aman untuk menghindari risiko.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            79 => array (
                'id' => 'SJO080', 'question_id' => 'SJ116', 'option_letter' => 'e', 'option_text' => 'Menunda keputusan sampai mendapatkan lebih banyak informasi atau kejelasan.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ117: Ketika menghadapi masalah besar, apa langkah pertama yang kamu lakukan? (PS)
            80 => array (
                'id' => 'SJO081', 'question_id' => 'SJ117', 'option_letter' => 'a', 'option_text' => 'Coba lihat masalahnya dari berbagai sisi dan mulai buat rencana.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            81 => array (
                'id' => 'SJO082', 'question_id' => 'SJ117', 'option_letter' => 'b', 'option_text' => 'Pecah masalahnya jadi bagian kecil, biar lebih mudah ditangani.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            82 => array (
                'id' => 'SJO083', 'question_id' => 'SJ117', 'option_letter' => 'c', 'option_text' => 'Saya perlu waktu untuk menganalisis permasalahan', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            83 => array (
                'id' => 'SJO084', 'question_id' => 'SJ117', 'option_letter' => 'd', 'option_text' => 'Menunda-nunda menghadapi masalah', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            84 => array (
                'id' => 'SJO085', 'question_id' => 'SJ117', 'option_letter' => 'e', 'option_text' => 'Lihat dulu apakah masalah ini bisa hilang dengan sendirinya.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ118: Jika solusi yang kamu pilih ternyata memiliki efek samping yang tidak terduga, bagaimana kamu menanganinya? (PS)
            85 => array (
                'id' => 'SJO086', 'question_id' => 'SJ118', 'option_letter' => 'a', 'option_text' => 'Segera menilai dampak sampingan dan menyesuaikan solusi untuk meminimalkan efeknya.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            86 => array (
                'id' => 'SJO087', 'question_id' => 'SJ118', 'option_letter' => 'b', 'option_text' => 'Mengidentifikasi masalah tersebut dan mencari cara untuk mengatasinya dengan cepat.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            87 => array (
                'id' => 'SJO088', 'question_id' => 'SJ118', 'option_letter' => 'c', 'option_text' => 'Memberikan penjelasan kepada pihak yang terlibat dan mencoba memperbaikinya.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            88 => array (
                'id' => 'SJO089', 'question_id' => 'SJ118', 'option_letter' => 'd', 'option_text' => 'Mengikuti prosedur untuk menangani masalah tersebut dan mencari solusi secara bertahap.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            89 => array (
                'id' => 'SJO090', 'question_id' => 'SJ118', 'option_letter' => 'e', 'option_text' => 'Mengabaikan efek samping dan melanjutkan dengan solusi yang sudah diterapkan.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ119: Jika kamu menemukan masalah yang belum pernah kamu hadapi, apa yang pertama kali kamu lakukan? (PS)
            90 => array (
                'id' => 'SJO091', 'question_id' => 'SJ119', 'option_letter' => 'a', 'option_text' => 'Cari tahu lebih banyak dulu, cari solusi yang belum terpikirkan.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            91 => array (
                'id' => 'SJO092', 'question_id' => 'SJ119', 'option_letter' => 'b', 'option_text' => 'Gunakan pendekatan yang pernah berhasil sebelumnya, mungkin bisa diterapkan.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            92 => array (
                'id' => 'SJO093', 'question_id' => 'SJ119', 'option_letter' => 'c', 'option_text' => 'Tanyakan ke teman atau rekan kerja yang mungkin punya pengalaman.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            93 => array (
                'id' => 'SJO094', 'question_id' => 'SJ119', 'option_letter' => 'd', 'option_text' => 'Ambil langkah pertama yang paling jelas dan lihat hasilnya.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            94 => array (
                'id' => 'SJO095', 'question_id' => 'SJ119', 'option_letter' => 'e', 'option_text' => 'Tunggu dulu, siapa tahu masalahnya akan selesai dengan sendirinya.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ120: Jika kamu tahu ada rekan kerja yang tidak jujur dalam pekerjaannya, apa yang kamu lakukan? (PE)
            95 => array (
                'id' => 'SJO096', 'question_id' => 'SJ120', 'option_letter' => 'a', 'option_text' => 'Bicara dengan rekan tersebut secara pribadi untuk menyarankan cara yang lebih baik.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            96 => array (
                'id' => 'SJO097', 'question_id' => 'SJ120', 'option_letter' => 'b', 'option_text' => 'Jika perlu, beri tahu atasan agar masalah bisa ditangani dengan tepat.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            97 => array (
                'id' => 'SJO098', 'question_id' => 'SJ120', 'option_letter' => 'c', 'option_text' => 'Diam saja dan berharap masalahnya cepat selesai sendiri.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            98 => array (
                'id' => 'SJO099', 'question_id' => 'SJ120', 'option_letter' => 'd', 'option_text' => 'Menghindar dan tidak terlalu ikut campur, biar mereka urus masalahnya sendiri.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            99 => array (
                'id' => 'SJO100', 'question_id' => 'SJ120', 'option_letter' => 'e', 'option_text' => 'Bergabung dengan mereka, karena semua orang juga melakukan hal serupa.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ121: Jika kamu terjebak dalam situasi di mana kamu harus memilih antara keuntungan pribadi dan kepentingan tim, apa yang kamu pilih? (PE)
            100 => array (
                'id' => 'SJO101', 'question_id' => 'SJ121', 'option_letter' => 'a', 'option_text' => 'Utamakan kepentingan tim, karena keberhasilan tim adalah keberhasilan bersama.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            101 => array (
                'id' => 'SJO102', 'question_id' => 'SJ121', 'option_letter' => 'b', 'option_text' => 'Pilih opsi yang memberi manfaat baik bagi dirimu maupun tim.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            102 => array (
                'id' => 'SJO103', 'question_id' => 'SJ121', 'option_letter' => 'c', 'option_text' => 'Tentukan pilihan yang paling menguntungkan bagi dirimu, karena itu akan memotivasi kerja lebih baik.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            103 => array (
                'id' => 'SJO104', 'question_id' => 'SJ121', 'option_letter' => 'd', 'option_text' => 'Utamakan dirimu, karena setiap orang harus memikirkan dirinya sendiri terlebih dahulu.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            104 => array (
                'id' => 'SJO105', 'question_id' => 'SJ121', 'option_letter' => 'e', 'option_text' => 'Pilih keuntungan pribadi, karena tak ada yang tahu soal keputusan tersebut.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ122: Jika kamu diberi kesempatan untuk mengambil kredit atas pekerjaan orang lain, apa yang akan kamu lakukan? (PE)
            105 => array (
                'id' => 'SJO106', 'question_id' => 'SJ122', 'option_letter' => 'a', 'option_text' => 'Tetap jujur dan memberikan penghargaan pada orang yang seharusnya mendapat kredit tersebut.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            106 => array (
                'id' => 'SJO107', 'question_id' => 'SJ122', 'option_letter' => 'b', 'option_text' => 'Ambil kredit untuk sebagian pekerjaan dan beri pengakuan pada orang lain untuk bagian lainnya.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            107 => array (
                'id' => 'SJO108', 'question_id' => 'SJ122', 'option_letter' => 'c', 'option_text' => 'Mengikuti saja apa yang dikreditkan untuk kita', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            108 => array (
                'id' => 'SJO109', 'question_id' => 'SJ122', 'option_letter' => 'd', 'option_text' => 'Saya tidak akan mengambil kredit itu meskipun saya juga memiliki jasa didalamnya', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            109 => array (
                'id' => 'SJO110', 'question_id' => 'SJ122', 'option_letter' => 'e', 'option_text' => 'Saya akan ambil kredit karena saya memberikan masukan dan solusi yang sangat berguna dibandingkan yang melaksankannya', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ123: Seberapa sering kamu menggunakan perangkat lunak atau alat tertentu yang relevan dengan pekerjaanmu? (GH)
            110 => array (
                'id' => 'SJO111', 'question_id' => 'SJ123', 'option_letter' => 'a', 'option_text' => 'Setiap hari, dan saya sangat menguasainya.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            111 => array (
                'id' => 'SJO112', 'question_id' => 'SJ123', 'option_letter' => 'b', 'option_text' => 'Cukup sering, saya merasa cukup nyaman menggunakannya.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            112 => array (
                'id' => 'SJO113', 'question_id' => 'SJ123', 'option_letter' => 'c', 'option_text' => 'Kadang-kadang, tapi saya masih perlu belajar lebih banyak.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            113 => array (
                'id' => 'SJO114', 'question_id' => 'SJ123', 'option_letter' => 'd', 'option_text' => 'Jarang, saya lebih memilih menggunakan cara manual atau tradisional.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            114 => array (
                'id' => 'SJO115', 'question_id' => 'SJ123', 'option_letter' => 'e', 'option_text' => 'Tidak pernah, saya tidak membutuhkan perangkat lunak tersebut.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ124: Seberapa yakin kamu dengan kemampuan teknismu menggunakan perangkat lunak atau alat kerja terkait pekerjaanmu? (GH)
            115 => array (
                'id' => 'SJO116', 'question_id' => 'SJ124', 'option_letter' => 'a', 'option_text' => 'Sangat yakin, saya bisa menggunakannya dengan sangat baik dan efisien.', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            116 => array (
                'id' => 'SJO117', 'question_id' => 'SJ124', 'option_letter' => 'b', 'option_text' => 'Cukup yakin, saya tahu cara menggunakannya meskipun masih ada hal-hal yang perlu dipelajari.', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            117 => array (
                'id' => 'SJO118', 'question_id' => 'SJ124', 'option_letter' => 'c', 'option_text' => 'Ragu-ragu, saya tahu dasar-dasarnya tapi masih sering bingung dengan fitur lanjutan.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            118 => array (
                'id' => 'SJO119', 'question_id' => 'SJ124', 'option_letter' => 'd', 'option_text' => 'Tidak yakin, saya masih merasa kesulitan dengan penggunaan alat ini.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            119 => array (
                'id' => 'SJO120', 'question_id' => 'SJ124', 'option_letter' => 'e', 'option_text' => 'Tidak tahu sama sekali, saya belum pernah menggunakannya.', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ125: Jika kamu diminta untuk memberikan pelatihan tentang perangkat lunak yang kamu kuasai kepada rekan kerja, bagaimana kamu melakukannya? (GH)
            120 => array (
                'id' => 'SJO121', 'question_id' => 'SJ125', 'option_letter' => 'a', 'option_text' => 'Tidak merasa cukup percaya diri untuk memberikan pelatihan', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            121 => array (
                'id' => 'SJO122', 'question_id' => 'SJ125', 'option_letter' => 'b', 'option_text' => 'Menunjukkan fitur dasar dan membiarkan rekan kerja belajar lebih lanjut sendiri.', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            122 => array (
                'id' => 'SJO123', 'question_id' => 'SJ125', 'option_letter' => 'c', 'option_text' => 'Saya buat materi pelatihan, tapi saya baca saja materinya.', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            123 => array (
                'id' => 'SJO124', 'question_id' => 'SJ125', 'option_letter' => 'd', 'option_text' => 'Saya berikan contoh-contoh kasus yang sering ditemui', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            124 => array (
                'id' => 'SJO125', 'question_id' => 'SJ125', 'option_letter' => 'e', 'option_text' => 'Saya menyesuaikan materi dengan kebutuhan dari rekan kerja saja', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ126: Kalau tugas lagi banyak dan deadline mepet, gimana biasanya kamu atur tugas-tugas itu? (SM)
            125 => array (
                'id' => 'SJO126', 'question_id' => 'SJ126', 'option_letter' => 'a', 'option_text' => 'Buat daftar prioritas dan urutkan mana yang paling penting', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            126 => array (
                'id' => 'SJO127', 'question_id' => 'SJ126', 'option_letter' => 'b', 'option_text' => 'Selesaikan tugas-tugas yang lebih mudah terlebih dahulu untuk mengurangi beban', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            127 => array (
                'id' => 'SJO128', 'question_id' => 'SJ126', 'option_letter' => 'c', 'option_text' => 'Kerjakan sesuai kondisi dan suasana hati saat itu', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            128 => array (
                'id' => 'SJO129', 'question_id' => 'SJ126', 'option_letter' => 'd', 'option_text' => 'Diskusikan dengan rekan untuk menentukan prioritas yang sebaiknya diselesaikan lebih dulu', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            129 => array (
                'id' => 'SJO130', 'question_id' => 'SJ126', 'option_letter' => 'e', 'option_text' => 'Mengerjakan mendekati deadline itu efektif', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ127: Bagaimana cara kamu menkontrol diri dari pekerjaan yang sangat banyak dengan beban kerja yang juga berat? (SM)
            130 => array (
                'id' => 'SJO131', 'question_id' => 'SJ127', 'option_letter' => 'a', 'option_text' => 'Saya akan merasa burnout dan mengambil jeda sejenak untuk membreakdown strategi penyelesaian', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            131 => array (
                'id' => 'SJO132', 'question_id' => 'SJ127', 'option_letter' => 'b', 'option_text' => 'Saya akan merasa sangat terbebani dengan pekerjaan yang sangat banyak, namun selalu saya usahakan selesai', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            132 => array (
                'id' => 'SJO133', 'question_id' => 'SJ127', 'option_letter' => 'c', 'option_text' => 'Saya akan merasa pusing sehingga saya mengambil waktu sejak sebelum melanjutkan pekerjaan', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            133 => array (
                'id' => 'SJO134', 'question_id' => 'SJ127', 'option_letter' => 'd', 'option_text' => 'Saya akan merasa strees, namun saya akan megerjakan sampai akhir', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            134 => array (
                'id' => 'SJO135', 'question_id' => 'SJ127', 'option_letter' => 'e', 'option_text' => 'Saya akan merasa burnout produktivitas akan turun, oleh karena itu saya perlu waktu yang lebih lama', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ128: Gimana caramu memastikan tugas selesai tepat waktu? (SM)
            135 => array (
                'id' => 'SJO136', 'question_id' => 'SJ128', 'option_letter' => 'a', 'option_text' => 'Metode SKS (SIstem kebut semalam)', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            136 => array (
                'id' => 'SJO137', 'question_id' => 'SJ128', 'option_letter' => 'b', 'option_text' => 'Mulai bekerja ketika suasana hati sedang mendukung', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            137 => array (
                'id' => 'SJO138', 'question_id' => 'SJ128', 'option_letter' => 'c', 'option_text' => 'Fokus penuh pada pekerjaan dan jauhkan diri dari hal-hal yang mengganggu', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            138 => array (
                'id' => 'SJO139', 'question_id' => 'SJ128', 'option_letter' => 'd', 'option_text' => 'Mulai kerjakan langsung tanpa menunda-nunda', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            139 => array (
                'id' => 'SJO140', 'question_id' => 'SJ128', 'option_letter' => 'e', 'option_text' => 'Buat daftar tugas harian (to-do list) agar semua langkah terencana', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SM', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ129: Kalau kamu dapat informasi dari beberapa sumber yang beda-beda, biasanya gimana kamu milih yang tepat? (TS)
            140 => array (
                'id' => 'SJO141', 'question_id' => 'SJ129', 'option_letter' => 'a', 'option_text' => 'Saya akan bertanya ke rekan saya mengenai opininya terhadap informasi itu', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            141 => array (
                'id' => 'SJO142', 'question_id' => 'SJ129', 'option_letter' => 'b', 'option_text' => 'Saya akan megambil informasi yang paling awal dan akhir saja karena abstraksi dan kesimpulan ada di awal dan diakhir', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            142 => array (
                'id' => 'SJO143', 'question_id' => 'SJ129', 'option_letter' => 'c', 'option_text' => 'Saya akan memilih informasi yang paling panjang isinya', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            143 => array (
                'id' => 'SJO144', 'question_id' => 'SJ129', 'option_letter' => 'd', 'option_text' => 'Saya akan membaca semua informasi dan mengambil informasi yang berulang', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            144 => array (
                'id' => 'SJO145', 'question_id' => 'SJ129', 'option_letter' => 'e', 'option_text' => 'Saya akan melihat sumber dari informasi saya dapatkan', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ130: Kalau ada masalah baru, apa langkah pertama yang kamu ambil? (TS)
            145 => array (
                'id' => 'SJO146', 'question_id' => 'SJ130', 'option_letter' => 'a', 'option_text' => 'Analisis akar masalahnya terlebih dahulu, lalu buat rencana penyelesaian', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            146 => array (
                'id' => 'SJO147', 'question_id' => 'SJ130', 'option_letter' => 'b', 'option_text' => 'Coba solusi pertama yang terpikirkan untuk melihat hasilnya', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            147 => array (
                'id' => 'SJO148', 'question_id' => 'SJ130', 'option_letter' => 'c', 'option_text' => 'Konsultasikan dengan rekan yang sudah berpengalaman dalam hal ini', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            148 => array (
                'id' => 'SJO149', 'question_id' => 'SJ130', 'option_letter' => 'd', 'option_text' => 'Cek referensi atau solusi serupa yang ada di internet', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            149 => array (
                'id' => 'SJO150', 'question_id' => 'SJ130', 'option_letter' => 'e', 'option_text' => 'Observasi situasi terlebih dahulu sebelum mengambil keputusan', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ131: Kalau dapat tugas yang susah, gimana caramu nentuin langkah yang harus diambil? (TS)
            150 => array (
                'id' => 'SJO151', 'question_id' => 'SJ131', 'option_letter' => 'a', 'option_text' => 'Berkonsultasi terlebih dahulu dengan rekan atau atasan yang lebih berpengalaman', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            151 => array (
                'id' => 'SJO152', 'question_id' => 'SJ131', 'option_letter' => 'b', 'option_text' => 'Membagi tugas mulai dari yang paling mudah dan bagian yang aku pahami sampai dengan bagian yang sulit dan tidak aku pahami', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            152 => array (
                'id' => 'SJO153', 'question_id' => 'SJ131', 'option_letter' => 'c', 'option_text' => 'Mencari referensi maupuan informasi sebelum mengerjakan tugas, jika refensi belum dapat maka tugas tidak bisa dikerjaka', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            153 => array (
                'id' => 'SJO154', 'question_id' => 'SJ131', 'option_letter' => 'd', 'option_text' => 'Mengerjakan kecil-kecil yang masih bisa dipahami terlebih dahulu ranpa ada perencanaan', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            154 => array (
                'id' => 'SJO155', 'question_id' => 'SJ131', 'option_letter' => 'e', 'option_text' => 'menunggu pencerahan untuk mengerjakan tugas itu', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'TS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ132: Gimana cara kamu supaya ide kamu dipahami sama tim? (CIA)
            155 => array (
                'id' => 'SJO156', 'question_id' => 'SJ132', 'option_letter' => 'a', 'option_text' => 'Jelaskan dengan apa adanya secara spontan', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            156 => array (
                'id' => 'SJO157', 'question_id' => 'SJ132', 'option_letter' => 'b', 'option_text' => 'Saya tidak peduli apakah ide saya diterima atau tidak', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            157 => array (
                'id' => 'SJO158', 'question_id' => 'SJ132', 'option_letter' => 'c', 'option_text' => 'Saya hanya menjelaskan ide saya kepada orang yang saya anggap penting', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            158 => array (
                'id' => 'SJO159', 'question_id' => 'SJ132', 'option_letter' => 'd', 'option_text' => 'Gunakan contoh konkret untuk memperjelas ide', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            159 => array (
                'id' => 'SJO160', 'question_id' => 'SJ132', 'option_letter' => 'e', 'option_text' => 'Saya antusias menjelaskan ide saya agar tim juga tertarik', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ133: Kalau ada pendapat yang beda dalam tim, gimana biasanya kamu menyikapi? (CIA)
            160 => array (
                'id' => 'SJO161', 'question_id' => 'SJ133', 'option_letter' => 'a', 'option_text' => 'membiarkan perbedaan pendapat karena itu adalah hak mereka', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            161 => array (
                'id' => 'SJO162', 'question_id' => 'SJ133', 'option_letter' => 'b', 'option_text' => 'mendengarkan pendapat yang berbeda dan mencoba memajami sudut pandangnya', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            162 => array (
                'id' => 'SJO163', 'question_id' => 'SJ133', 'option_letter' => 'c', 'option_text' => 'Saya akan berpihak kepada orang yang saya kenal dekat', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            163 => array (
                'id' => 'SJO164', 'question_id' => 'SJ133', 'option_letter' => 'd', 'option_text' => 'Saya akan mengikuti suara mayoritas saja', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            164 => array (
                'id' => 'SJO165', 'question_id' => 'SJ133', 'option_letter' => 'e', 'option_text' => 'Saya akan menjadi fasilitastor menengahi perbedaan pendapat untuk mencapai kesepakatan', // Opsi Text Diperbarui (f menjadi e)
                'score' => 4, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ134: Kalau temen kerja lagi down, apa yang kamu lakuin buat bantu? (CIA)
            165 => array (
                'id' => 'SJO166', 'question_id' => 'SJ134', 'option_letter' => 'a', 'option_text' => 'Dengarkan dan berikan semangat agar mereka lebih optimis', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            166 => array (
                'id' => 'SJO167', 'question_id' => 'SJ134', 'option_letter' => 'b', 'option_text' => 'Biarkan mereka punya waktu sendiri sampai siap kembali', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            167 => array (
                'id' => 'SJO168', 'question_id' => 'SJ134', 'option_letter' => 'c', 'option_text' => 'Ajak ngobrol ringan untuk menciptakan suasana santai', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            168 => array (
                'id' => 'SJO169', 'question_id' => 'SJ134', 'option_letter' => 'd', 'option_text' => 'Ajak mereka rehat sejenak atau jalan-jalan supaya lebih rileks', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            169 => array (
                'id' => 'SJO170', 'question_id' => 'SJ134', 'option_letter' => 'e', 'option_text' => 'Bantu meringankan beban tugasnya untuk sementara', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CIA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ135: Kalau ada yang nggak aktif di tim, gimana cara kamu ngajak dia supaya lebih aktif? (WWO)
            170 => array (
                'id' => 'SJO171', 'question_id' => 'SJ135', 'option_letter' => 'a', 'option_text' => 'Berikan dukungan dan motivasi untuk ikut aktif', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            171 => array (
                'id' => 'SJO172', 'question_id' => 'SJ135', 'option_letter' => 'b', 'option_text' => 'Tanyakan alasannya agar bisa memahami kendalanya', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            172 => array (
                'id' => 'SJO173', 'question_id' => 'SJ135', 'option_letter' => 'c', 'option_text' => 'Tunggu saja, berharap nanti mereka akan ikut sendiri', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            173 => array (
                'id' => 'SJO174', 'question_id' => 'SJ135', 'option_letter' => 'd', 'option_text' => 'Libatkan mereka dalam tugas-tugas kecil terlebih dahulu', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            174 => array (
                'id' => 'SJO175', 'question_id' => 'SJ135', 'option_letter' => 'e', 'option_text' => 'Bicara secara baik-baik agar mereka merasa nyaman berpartisipasi', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ136: Kalau tim punya pendapat yang beda-beda, gimana kamu biar semuanya bisa sepakat? (WWO)
            175 => array (
                'id' => 'SJO176', 'question_id' => 'SJ136', 'option_letter' => 'a', 'option_text' => 'Karena keduanya sama-sama keras kepala maka akan saya biarkan saja, selama tidak ada kekerasan yang terjadi', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            176 => array (
                'id' => 'SJO177', 'question_id' => 'SJ136', 'option_letter' => 'b', 'option_text' => 'Saya akan mengikuti suara mayoritas', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            177 => array (
                'id' => 'SJO178', 'question_id' => 'SJ136', 'option_letter' => 'c', 'option_text' => 'Saya akan memberikan pendapat saya pribadi', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            178 => array (
                'id' => 'SJO179', 'question_id' => 'SJ136', 'option_letter' => 'd', 'option_text' => 'Saya akan mencoba mendengar pendapat semua orang', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            179 => array (
                'id' => 'SJO180', 'question_id' => 'SJ136', 'option_letter' => 'e', 'option_text' => 'Diskusikan cara untuk mencapai kompromi agar semua merasa nyaman', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ137: Gimana cara kamu biar bisa kerjasama dengan orang yang punya cara kerja beda? (WWO)
            180 => array (
                'id' => 'SJO181', 'question_id' => 'SJ137', 'option_letter' => 'a', 'option_text' => 'Menyesuaikan diri dengan cara kerja mereka', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            181 => array (
                'id' => 'SJO182', 'question_id' => 'SJ137', 'option_letter' => 'b', 'option_text' => 'Tetap dengan pendekatan pribadi', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            182 => array (
                'id' => 'SJO183', 'question_id' => 'SJ137', 'option_letter' => 'c', 'option_text' => 'Cari titik tengah agar keduanya nyaman', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            183 => array (
                'id' => 'SJO184', 'question_id' => 'SJ137', 'option_letter' => 'd', 'option_text' => 'Pelajari cara mereka untuk lebih mudah beradaptasi', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            184 => array (
                'id' => 'SJO185', 'question_id' => 'SJ137', 'option_letter' => 'e', 'option_text' => 'Diskusikan cara kerja masing-masing untuk saling memahami', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'WWO', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ138: Gimana caramu nunjukin kalau kamu punya semangat buat berkembang? (CA)
            185 => array (
                'id' => 'SJO186', 'question_id' => 'SJ138', 'option_letter' => 'a', 'option_text' => 'Mengikuti pelatihan tambahan yang bisa meningkatkan keterampilan', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            186 => array (
                'id' => 'SJO187', 'question_id' => 'SJ138', 'option_letter' => 'b', 'option_text' => 'Menunggu kesempatan baru datang dan siap mengambil peluang', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            187 => array (
                'id' => 'SJO188', 'question_id' => 'SJ138', 'option_letter' => 'c', 'option_text' => 'Belajar mandiri dan mengembangkan diri saat ada waktu luang', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            188 => array (
                'id' => 'SJO189', 'question_id' => 'SJ138', 'option_letter' => 'd', 'option_text' => 'Mengikuti aktivitas yang diambil oleh rekan kerja', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            189 => array (
                'id' => 'SJO190', 'question_id' => 'SJ138', 'option_letter' => 'e', 'option_text' => 'Mencari tugas-tugas baru untuk mendapatkan lebih banyak pengalaman', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ139: Kalau ada hambatan di kerjaan, apa yang biasanya kamu lakuin? (CA)
            190 => array (
                'id' => 'SJO191', 'question_id' => 'SJ139', 'option_letter' => 'a', 'option_text' => 'Mencari cara sendiri untuk menyelesaikan masalah tersebut', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            191 => array (
                'id' => 'SJO192', 'question_id' => 'SJ139', 'option_letter' => 'b', 'option_text' => 'Tanya rekan atau atasan yang mungkin lebih berpengalaman', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            192 => array (
                'id' => 'SJO193', 'question_id' => 'SJ139', 'option_letter' => 'c', 'option_text' => 'Biarkan saja jika merasa masalahnya terlalu sulit', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            193 => array (
                'id' => 'SJO194', 'question_id' => 'SJ139', 'option_letter' => 'd', 'option_text' => 'Mencari solusi atau referensi dari sumber online', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            194 => array (
                'id' => 'SJO195', 'question_id' => 'SJ139', 'option_letter' => 'e', 'option_text' => 'Menghadapinya dan jika tidak mengetahui caranya saya akan bertanya', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ140: Kalau dikasih tugas baru, gimana sikap kamu buat belajar hal-hal baru? (CA)
            195 => array (
                'id' => 'SJO196', 'question_id' => 'SJ140', 'option_letter' => 'a', 'option_text' => 'Mengambil tugas itu dengan percaya diri dan yakin bisa menyelesaikan', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            196 => array (
                'id' => 'SJO197', 'question_id' => 'SJ140', 'option_letter' => 'b', 'option_text' => 'menerima saja meskipun tidak terlalu antusias', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            197 => array (
                'id' => 'SJO198', 'question_id' => 'SJ140', 'option_letter' => 'c', 'option_text' => 'Belajar seadanya yang penting tugas selesai', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            198 => array (
                'id' => 'SJO199', 'question_id' => 'SJ140', 'option_letter' => 'd', 'option_text' => 'Pelajari secara bertahap agar lebih paham', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            199 => array (
                'id' => 'SJO200', 'question_id' => 'SJ140', 'option_letter' => 'e', 'option_text' => 'Mencari rekan kerja yang memiliki tugas yang sama, biar bisa bekerjasama', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'CA', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ141: Kalau nggak ada yang ambil inisiatif di tim, gimana cara kamu biasanya bertindak? (L)
            200 => array (
                'id' => 'SJO201', 'question_id' => 'SJ141', 'option_letter' => 'a', 'option_text' => 'Langsung mengambil inisiatif sendiri', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            201 => array (
                'id' => 'SJO202', 'question_id' => 'SJ141', 'option_letter' => 'b', 'option_text' => 'Menunggu sampai ada yang mulai', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            202 => array (
                'id' => 'SJO203', 'question_id' => 'SJ141', 'option_letter' => 'c', 'option_text' => 'Memberikan saran agar tim bisa mulai bergerak', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            203 => array (
                'id' => 'SJO204', 'question_id' => 'SJ141', 'option_letter' => 'd', 'option_text' => 'Mengajak diskusi tim untuk mencari solusi bersama', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            204 => array (
                'id' => 'SJO205', 'question_id' => 'SJ141', 'option_letter' => 'e', 'option_text' => 'Menunggu arahan dari atasan', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ142: Kalau ada anggota tim yang kurang semangat, apa yang kamu lakuin biar dia termotivasi? (L)
            205 => array (
                'id' => 'SJO206', 'question_id' => 'SJ142', 'option_letter' => 'a', 'option_text' => 'Memberikan semangat dan menjadi contoh yang baik', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            206 => array (
                'id' => 'SJO207', 'question_id' => 'SJ142', 'option_letter' => 'b', 'option_text' => 'Memberikan tugas agar mereka lebih sibuk dan fokus', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            207 => array (
                'id' => 'SJO208', 'question_id' => 'SJ142', 'option_letter' => 'c', 'option_text' => 'Membiarkan mereka untuk menemukan semangatnya sendiri', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            208 => array (
                'id' => 'SJO209', 'question_id' => 'SJ142', 'option_letter' => 'd', 'option_text' => 'Menengurnya dan mengingatkannya', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            209 => array (
                'id' => 'SJO210', 'question_id' => 'SJ142', 'option_letter' => 'e', 'option_text' => 'Membantu mencari solusi untuk meningkatkan motivasi mereka', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'L', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ143: Gimana perasaan kamu kalau dikasih tugas baru yang menantang? (SE)
            210 => array (
                'id' => 'SJO211', 'question_id' => 'SJ143', 'option_letter' => 'a', 'option_text' => 'Tidak tertarik karena bukan jobdesk utama saya', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            211 => array (
                'id' => 'SJO212', 'question_id' => 'SJ143', 'option_letter' => 'b', 'option_text' => 'Mengomel karena tugas tambahan yang tidak sesuai dengan keahlian', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            212 => array (
                'id' => 'SJO213', 'question_id' => 'SJ143', 'option_letter' => 'c', 'option_text' => 'Strees dan Overthinking duluan takut ngga bisa ngerjain', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            213 => array (
                'id' => 'SJO214', 'question_id' => 'SJ143', 'option_letter' => 'd', 'option_text' => 'Menerima kemudian mencoba dan belajar', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            214 => array (
                'id' => 'SJO215', 'question_id' => 'SJ143', 'option_letter' => 'e', 'option_text' => 'Merasa lebih percaya diri untuk mengambil tantangan', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ144: Kalau dapat kritik dari atasan, gimana cara kamu menghadapinya? (SE)
            215 => array (
                'id' => 'SJO216', 'question_id' => 'SJ144', 'option_letter' => 'a', 'option_text' => 'Mencari pembenaran dari kritik yang diberikan untuk membenarkan diri', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            216 => array (
                'id' => 'SJO217', 'question_id' => 'SJ144', 'option_letter' => 'b', 'option_text' => 'Diam saja menerima kritikan', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            217 => array (
                'id' => 'SJO218', 'question_id' => 'SJ144', 'option_letter' => 'c', 'option_text' => 'Saya merasa down dan tidak bersemangat bekerja', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            218 => array (
                'id' => 'SJO219', 'question_id' => 'SJ144', 'option_letter' => 'd', 'option_text' => 'Berterima kasih atas masukan yang diberikan dan berusaha memperbaikinya', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            219 => array (
                'id' => 'SJO220', 'question_id' => 'SJ144', 'option_letter' => 'e', 'option_text' => 'Menerima kritik dan evaluasi diri mencari tau apa yang harus ditingkatkan', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'SE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ145: Kalau kamu nemuin masalah baru di kerjaan, biasanya apa langkah pertama kamu? (PS)
            220 => array (
                'id' => 'SJO221', 'question_id' => 'SJ145', 'option_letter' => 'a', 'option_text' => 'Menganalisis penyebab masalah sebelum membuat keputusan', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            221 => array (
                'id' => 'SJO222', 'question_id' => 'SJ145', 'option_letter' => 'b', 'option_text' => 'Mencoba solusi pertama yang terlintas dalam pikiran', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            222 => array (
                'id' => 'SJO223', 'question_id' => 'SJ145', 'option_letter' => 'c', 'option_text' => 'Bertanya kepada rekan yang lebih berpengalaman', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            223 => array (
                'id' => 'SJO224', 'question_id' => 'SJ145', 'option_letter' => 'd', 'option_text' => 'Mencari referensi solusi di internet', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            224 => array (
                'id' => 'SJO225', 'question_id' => 'SJ145', 'option_letter' => 'e', 'option_text' => 'Mengamati lebih lanjut dan menunggu sebelum mengambil langkah', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ146: Kalau solusi pertama gagal, gimana cara kamu mencari alternatif lain? (PS)
            225 => array (
                'id' => 'SJO226', 'question_id' => 'SJ146', 'option_letter' => 'a', 'option_text' => 'Segera mencari solusi lain yang mungkin lebih cocok', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            226 => array (
                'id' => 'SJO227', 'question_id' => 'SJ146', 'option_letter' => 'b', 'option_text' => 'Bertanya kepada rekan atau atasan untuk saran lebih lanjut', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            227 => array (
                'id' => 'SJO228', 'question_id' => 'SJ146', 'option_letter' => 'c', 'option_text' => 'Mengabaikan dan melanjutkan tugas lain', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            228 => array (
                'id' => 'SJO229', 'question_id' => 'SJ146', 'option_letter' => 'd', 'option_text' => 'Mengevaluasi kesalahan dari solusi pertama untuk memperbaiki', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            229 => array (
                'id' => 'SJO230', 'question_id' => 'SJ146', 'option_letter' => 'e', 'option_text' => 'Mempelajari pendekatan lain yang belum dicoba', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PS', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ147: Kalau ada rekan yang melakukan pelanggaran aturan, gimana cara kamu bertindak? (PE)
            230 => array (
                'id' => 'SJO231', 'question_id' => 'SJ147', 'option_letter' => 'a', 'option_text' => 'Melaporkan kepada pihak yang bertugas karena dilakukan secara sadara diri', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            231 => array (
                'id' => 'SJO232', 'question_id' => 'SJ147', 'option_letter' => 'b', 'option_text' => 'Memberikan nasihat pribadi kepada rekan tersebut dan menindak keras jika terjadi kembali', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            232 => array (
                'id' => 'SJO233', 'question_id' => 'SJ147', 'option_letter' => 'c', 'option_text' => 'Mengabaikan karena merasa itu bukan urusan', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            233 => array (
                'id' => 'SJO234', 'question_id' => 'SJ147', 'option_letter' => 'd', 'option_text' => 'Memberikan peringatan secara anonim dan melaporkan secara anonim', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            234 => array (
                'id' => 'SJO235', 'question_id' => 'SJ147', 'option_letter' => 'e', 'option_text' => 'Menyebarkan pelanggaran itu ke rekan lainnya', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ148: Saat dikasih tugas yang susah, gimana kamu memastikan tetap bertindak jujur dan bertanggung jawab? (PE)
            235 => array (
                'id' => 'SJO236', 'question_id' => 'SJ148', 'option_letter' => 'a', 'option_text' => 'Memastikan bahwa semua data dan pekerjaan sesuai dengan standar yang ditetapkan', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            236 => array (
                'id' => 'SJO237', 'question_id' => 'SJ148', 'option_letter' => 'b', 'option_text' => 'Mengusahakan yang terbaik semampu mungkin dalam menyelesaikan tugas', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            237 => array (
                'id' => 'SJO238', 'question_id' => 'SJ148', 'option_letter' => 'c', 'option_text' => 'Mengabaikan detail kecil agar pekerjaan cepat selesai', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            238 => array (
                'id' => 'SJO239', 'question_id' => 'SJ148', 'option_letter' => 'd', 'option_text' => 'Bertanya jika ada bagian yang kurang jelas', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            239 => array (
                'id' => 'SJO240', 'question_id' => 'SJ148', 'option_letter' => 'e', 'option_text' => 'Merevisi hasil jika ditemukan kesalahan di akhir pekerjaan', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'PE', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ149: Kalau ada teknologi atau alat baru yang perlu dipelajari, gimana biasanya kamu menyikapinya? (GH)
            240 => array (
                'id' => 'SJO241', 'question_id' => 'SJ149', 'option_letter' => 'a', 'option_text' => 'Menunggu arahan untuk menggunakannya', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            241 => array (
                'id' => 'SJO242', 'question_id' => 'SJ149', 'option_letter' => 'b', 'option_text' => 'jika ada keperluan baru mengakses dan mempelajarinya', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            242 => array (
                'id' => 'SJO243', 'question_id' => 'SJ149', 'option_letter' => 'c', 'option_text' => 'Coba-coba sendiri jika ada waktu', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            243 => array (
                'id' => 'SJO244', 'question_id' => 'SJ149', 'option_letter' => 'd', 'option_text' => 'Mencari tutorial atau materi terkait secara online', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            244 => array (
                'id' => 'SJO245', 'question_id' => 'SJ149', 'option_letter' => 'e', 'option_text' => 'Latihan secara bertahap untuk memahami lebih baik', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            // SJ150: Kalau dikasih tugas teknis yang kamu belum kuasai, apa yang kamu lakukan? (GH)
            245 => array (
                'id' => 'SJO246', 'question_id' => 'SJ150', 'option_letter' => 'a', 'option_text' => 'Saya akan menyampaikan bahwa saya tidak menguasainya', // Opsi Text Diperbarui
                'score' => 0, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            246 => array (
                'id' => 'SJO247', 'question_id' => 'SJ150', 'option_letter' => 'b', 'option_text' => 'saya mencari rekan yang memahami dan meminta tolong padanya untuk menggantikan', // Opsi Text Diperbarui
                'score' => 1, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            247 => array (
                'id' => 'SJO248', 'question_id' => 'SJ150', 'option_letter' => 'c', 'option_text' => 'saya akan mencoba-coba jika mudah saya suka, namun jika sulit saya akan menunggu diajarkan', // Opsi Text Diperbarui
                'score' => 2, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            248 => array (
                'id' => 'SJO249', 'question_id' => 'SJ150', 'option_letter' => 'd', 'option_text' => 'saya akan meminta tolong kepada teman saya yang menguasai untuk membantu saya', // Opsi Text Diperbarui
                'score' => 3, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
            249 => array (
                'id' => 'SJO250', 'question_id' => 'SJ150', 'option_letter' => 'e', 'option_text' => 'saya akan meminta rekan/atasan mengajari saya dan mencari sumber pembelajaran', // Opsi Text Diperbarui
                'score' => 4, 'competency_target' => 'GH', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ),
        ));
    }
}
