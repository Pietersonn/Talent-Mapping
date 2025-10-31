<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SjtQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('sjt_questions')->delete();

        DB::table('sjt_questions')->insert(array (
            0 =>
            array (
                'id' => 'SJ101',
                'version_id' => 'SJV01',
                'number' => 1,
                'question_text' => 'Bagaimana cara kamu mengatur waktu untuk tugas yang butuh fokus tinggi?',
                'competency' => 'SM',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            1 =>
            array (
                'id' => 'SJ102',
                'version_id' => 'SJV01',
                'number' => 2,
                'question_text' => 'Kalau ada situasi bikin emosi di tempat kerja, gimana caramu?',
                'competency' => 'SM',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            2 =>
            array (
                'id' => 'SJ103',
                'version_id' => 'SJV01',
                'number' => 3,
                'question_text' => 'Saat menghadapi masalah yang belum pernah kamu temui sebelumnya, apa yang kamu lakukan?',
                'competency' => 'TS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            3 =>
            array (
                'id' => 'SJ104',
                'version_id' => 'SJV01',
                'number' => 4,
                'question_text' => 'Bagaimana kamu mengevaluasi keputusan yang sudah dibuat?',
                'competency' => 'TS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            4 =>
            array (
                'id' => 'SJ105',
                'version_id' => 'SJV01',
                'number' => 5,
                'question_text' => 'Saat kamu ingin menyampaikan ide baru kepada tim, bagaimana cara terbaik untuk melakukannya?',
                'competency' => 'CIA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            5 =>
            array (
                'id' => 'SJ106',
                'version_id' => 'SJV01',
                'number' => 6,
                'question_text' => 'Saat ada diskusi kelompok, bagaimana kamu memastikan semua orang ikut berpartisipasi?',
                'competency' => 'CIA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            6 =>
            array (
                'id' => 'SJ107',
                'version_id' => 'SJV01',
                'number' => 7,
                'question_text' => 'Jika kamu bekerja dengan rekan yang memiliki cara kerja berbeda, bagaimana kamu menyesuaikan diri?',
                'competency' => 'WWO',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            7 =>
            array (
                'id' => 'SJ108',
                'version_id' => 'SJV01',
                'number' => 8,
                'question_text' => 'Ketika tugas dibagi dalam tim, apa yang biasanya kamu lakukan untuk memastikan semuanya berjalan lancar?',
                'competency' => 'WWO',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            8 =>
            array (
                'id' => 'SJ109',
                'version_id' => 'SJV01',
                'number' => 9,
                'question_text' => 'Bagaimana kamu menjaga motivasi dalam bekerja, terutama saat menghadapi tugas yang monoton?',
                'competency' => 'CA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            9 =>
            array (
                'id' => 'SJ110',
                'version_id' => 'SJV01',
                'number' => 10,
                'question_text' => 'Saat kamu menghadapi kesulitan dalam pekerjaan, apa yang membantu kamu untuk tetap semangat?',
                'competency' => 'CA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            10 =>
            array (
                'id' => 'SJ111',
                'version_id' => 'SJV01',
                'number' => 11,
                'question_text' => 'Bagaimana kamu menjaga semangat dan motivasi dalam tim ketika menghadapi tantangan besar?',
                'competency' => 'L',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            11 =>
            array (
                'id' => 'SJ112',
                'version_id' => 'SJV01',
                'number' => 12,
                'question_text' => 'Ketika tim membutuhkan seseorang untuk bertanggung jawab mengambil keputusan penting, apa yang kamu lakukan?',
                'competency' => 'L',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            12 =>
            array (
                'id' => 'SJ113',
                'version_id' => 'SJV01',
                'number' => 13,
                'question_text' => 'Bagaimana kamu menginspirasi rekan kerja melalui tindakan dan kata-kata?',
                'competency' => 'L',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            13 =>
            array (
                'id' => 'SJ114',
                'version_id' => 'SJV01',
                'number' => 14,
                'question_text' => 'Bagaimana kamu menunjukkan rasa percaya diri saat mempresentasikan ide di depan orang banyak?',
                'competency' => 'SE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            14 =>
            array (
                'id' => 'SJ115',
                'version_id' => 'SJV01',
                'number' => 15,
                'question_text' => 'Bagaimana kamu memperlakukan kegagalan atau kesalahan yang kamu buat?',
                'competency' => 'SE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            15 =>
            array (
                'id' => 'SJ116',
                'version_id' => 'SJV01',
                'number' => 16,
                'question_text' => 'Ketika menghadapi situasi baru yang memerlukan keputusan cepat, bagaimana kamu mengatasi rasa ragu?',
                'competency' => 'SE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            16 =>
            array (
                'id' => 'SJ117',
                'version_id' => 'SJV01',
                'number' => 17,
                'question_text' => 'Ketika menghadapi masalah besar, apa langkah pertama yang kamu ambil?',
                'competency' => 'PS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            17 =>
            array (
                'id' => 'SJ118',
                'version_id' => 'SJV01',
                'number' => 18,
                'question_text' => 'Jika solusi yang kamu pilih ternyata memiliki efek samping yang tidak diinginkan, apa yang kamu lakukan?',
                'competency' => 'PS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            18 =>
            array (
                'id' => 'SJ119',
                'version_id' => 'SJV01',
                'number' => 19,
                'question_text' => 'Jika kamu menemukan masalah yang belum pernah kamu hadapi sebelumnya, bagaimana pendekatan kamu?',
                'competency' => 'PS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            19 =>
            array (
                'id' => 'SJ120',
                'version_id' => 'SJV01',
                'number' => 20,
                'question_text' => 'Jika kamu tahu ada rekan kerja yang tidak jujur dalam melaporkan hasil kerja, apa yang akan kamu lakukan?',
                'competency' => 'PE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            20 =>
            array (
                'id' => 'SJ121',
                'version_id' => 'SJV01',
                'number' => 21,
                'question_text' => 'Jika kamu terjebak dalam situasi di mana kamu harus memilih antara keuntungan pribadi dan kejujuran, apa yang kamu pilih?',
                'competency' => 'PE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            21 =>
            array (
                'id' => 'SJ122',
                'version_id' => 'SJV01',
                'number' => 22,
                'question_text' => 'Jika kamu diberi kesempatan untuk mengambil kredit atas pekerjaan orang lain, apa yang akan kamu lakukan?',
                'competency' => 'PE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            22 =>
            array (
                'id' => 'SJ123',
                'version_id' => 'SJV01',
                'number' => 23,
                'question_text' => 'Seberapa sering kamu menggunakan perangkat lunak atau aplikasi baru untuk mendukung pekerjaan?',
                'competency' => 'GH',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            23 =>
            array (
                'id' => 'SJ124',
                'version_id' => 'SJV01',
                'number' => 24,
                'question_text' => 'Seberapa yakin kamu dengan kemampuan teknismu menggunakan perangkat digital untuk menyelesaikan tugas?',
                'competency' => 'GH',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            24 =>
            array (
                'id' => 'SJ125',
                'version_id' => 'SJV01',
                'number' => 25,
                'question_text' => 'Jika kamu diminta untuk memberikan pelatihan tentang keterampilan teknis kepada rekan kerja, bagaimana kamu merasa?',
                'competency' => 'GH',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            25 =>
            array (
                'id' => 'SJ126',
                'version_id' => 'SJV01',
                'number' => 26,
                'question_text' => 'Kalau tugas lagi banyak dan deadline mepet, gimana cara kamu mengatasi stress?',
                'competency' => 'SM',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            26 =>
            array (
                'id' => 'SJ127',
                'version_id' => 'SJV01',
                'number' => 27,
                'question_text' => 'Bagaimana cara kamu menkontrol diri dari pekerjaan yang bikin kesal?',
                'competency' => 'SM',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            27 =>
            array (
                'id' => 'SJ128',
                'version_id' => 'SJV01',
                'number' => 28,
                'question_text' => 'Gimana caramu memastikan tugas selesai tepat waktu tanpa bikin diri kelelahan?',
                'competency' => 'SM',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            28 =>
            array (
                'id' => 'SJ129',
                'version_id' => 'SJV01',
                'number' => 29,
                'question_text' => 'Kalau kamu dapat informasi dari beberapa sumber yang berbeda, gimana caramu memilah mana yang bener?',
                'competency' => 'TS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            29 =>
            array (
                'id' => 'SJ130',
                'version_id' => 'SJV01',
                'number' => 30,
                'question_text' => 'Kalau ada masalah baru, apa langkah pertama yang kamu lakuin?',
                'competency' => 'TS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            30 =>
            array (
                'id' => 'SJ131',
                'version_id' => 'SJV01',
                'number' => 31,
                'question_text' => 'Kalau dapat tugas yang susah, gimana caramu nentuin prioritas penyelesaiannya?',
                'competency' => 'TS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            31 =>
            array (
                'id' => 'SJ132',
                'version_id' => 'SJV01',
                'number' => 32,
                'question_text' => 'Gimana cara kamu supaya ide kamu dipahami sama tim dengan jelas?',
                'competency' => 'CIA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            32 =>
            array (
                'id' => 'SJ133',
                'version_id' => 'SJV01',
                'number' => 33,
                'question_text' => 'Kalau ada pendapat yang beda dalam tim, gimana biasanya kamu nyikapi?',
                'competency' => 'CIA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            33 =>
            array (
                'id' => 'SJ134',
                'version_id' => 'SJV01',
                'number' => 34,
                'question_text' => 'Kalau temen kerja lagi down, apa yang kamu lakuin buat bantuin dia?',
                'competency' => 'CIA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            34 =>
            array (
                'id' => 'SJ135',
                'version_id' => 'SJV01',
                'number' => 35,
                'question_text' => 'Kalau ada yang nggak aktif di tim, gimana cara kamu buat ngedorong mereka ikut kontribusi?',
                'competency' => 'WWO',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            35 =>
            array (
                'id' => 'SJ136',
                'version_id' => 'SJV01',
                'number' => 36,
                'question_text' => 'Kalau tim punya pendapat yang beda-beda, gimana kamu bantuin biar bisa cepet sepakat?',
                'competency' => 'WWO',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            36 =>
            array (
                'id' => 'SJ137',
                'version_id' => 'SJV01',
                'number' => 37,
                'question_text' => 'Gimana cara kamu biar bisa kerjasama dengan orang yang karakternya beda banget?',
                'competency' => 'WWO',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            37 =>
            array (
                'id' => 'SJ138',
                'version_id' => 'SJV01',
                'number' => 38,
                'question_text' => 'Gimana caramu nunjukin kalau kamu punya semangat belajar dan berkembang?',
                'competency' => 'CA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            38 =>
            array (
                'id' => 'SJ139',
                'version_id' => 'SJV01',
                'number' => 39,
                'question_text' => 'Kalau ada hambatan di kerjaan, apa yang biasanya kamu lakuin?',
                'competency' => 'CA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            39 =>
            array (
                'id' => 'SJ140',
                'version_id' => 'SJV01',
                'number' => 40,
                'question_text' => 'Kalau dikasih tugas baru, gimana sikap kamu buat pastiin berhasil?',
                'competency' => 'CA',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            40 =>
            array (
                'id' => 'SJ141',
                'version_id' => 'SJV01',
                'number' => 41,
                'question_text' => 'Kalau nggak ada yang ambil inisiatif di tim, gimana caramu buat gerakin mereka?',
                'competency' => 'L',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            41 =>
            array (
                'id' => 'SJ142',
                'version_id' => 'SJV01',
                'number' => 42,
                'question_text' => 'Kalau ada anggota tim yang kurang semangat, apa yang kamu lakuin buat motifasi mereka?',
                'competency' => 'L',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            42 =>
            array (
                'id' => 'SJ143',
                'version_id' => 'SJV01',
                'number' => 43,
                'question_text' => 'Gimana perasaan kamu kalau dikasih tugas baru yang belum pernah kamu kerjain?',
                'competency' => 'SE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            43 =>
            array (
                'id' => 'SJ144',
                'version_id' => 'SJV01',
                'number' => 44,
                'question_text' => 'Kalau dapat kritik dari atasan, gimana cara kamu merespon dan belajar dari itu?',
                'competency' => 'SE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            44 =>
            array (
                'id' => 'SJ145',
                'version_id' => 'SJV01',
                'number' => 45,
                'question_text' => 'Kalau kamu nemuin masalah baru di kerjaan, biasanya langkah pertama yang kamu ambil apa?',
                'competency' => 'PS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            45 =>
            array (
                'id' => 'SJ146',
                'version_id' => 'SJV01',
                'number' => 46,
                'question_text' => 'Kalau solusi pertama gagal, gimana cara kamu mencari alternatif lain?',
                'competency' => 'PS',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            46 =>
            array (
                'id' => 'SJ147',
                'version_id' => 'SJV01',
                'number' => 47,
                'question_text' => 'Kalau ada rekan yang melakukan pelanggaran aturan, gimana kamu nyikapi?',
                'competency' => 'PE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            47 =>
            array (
                'id' => 'SJ148',
                'version_id' => 'SJV01',
                'number' => 48,
                'question_text' => 'Saat dikasih tugas yang susah, gimana kamu memastikan tetap jujur sama kemampuan diri?',
                'competency' => 'PE',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            48 =>
            array (
                'id' => 'SJ149',
                'version_id' => 'SJV01',
                'number' => 49,
                'question_text' => 'Kalau ada teknologi atau alat baru yang perlu dipelajari buat kerja, gimana cara kamu belajarnya?',
                'competency' => 'GH',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
            49 =>
            array (
                'id' => 'SJ150',
                'version_id' => 'SJV01',
                'number' => 50,
                'question_text' => 'Kalau dikasih tugas teknis yang kamu belum kuasai, gimana caramu hadapi?',
                'competency' => 'GH',
                'is_active' => 1,
                'created_at' => '2025-08-20 20:40:23',
                'updated_at' => '2025-08-20 20:40:23',
            ),
        ));


    }
}
