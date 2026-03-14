<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeskripsiKompetensiTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('deskripsi_kompetensi')->delete();

        DB::table('deskripsi_kompetensi')->insert([
            [
                'id'                      => 1,
                'kode_kompetensi'         => 'SM',
                'nama_kompetensi'         => 'Self Management',
                'deskripsi_kekuatan'      => 'Kamu bisa memanajemen waktu dengan efektif, disiplin tinggi, serta menjaga work-life balance dengan baik. Kamu juga mampu mengorganisir dan merencanakan kegiatan dengan baik untuk mencapai tujuan.',
                'deskripsi_kelemahan'     => 'Kamu cenderung terlalu fokus sendiri, kurang terbuka berdiskusi atau meminta bantuan, dan belum cukup fleksibel dalam manajemen waktu.',
                'aktivitas_pengembangan'  => 'Tetapkan target jangka pendek dan panjang secara realistis. Cari mentor untuk bimbingan karier dan motivasi kerja. Evaluasi dan apresiasi progres pribadi secara berkala.',
                'rekomendasi_pelatihan'   => 'Time Management for Peak Performance | Manajemen Emosi: Kecerdasan Emosional untuk Keunggulan Profesional | Job Priority & Strategic Task Prioritization',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 2,
                'kode_kompetensi'         => 'CIA',
                'nama_kompetensi'         => 'Communication & Interpersonal Ability',
                'deskripsi_kekuatan'      => 'Kamu orang yang bertanggung jawab, pendengar yang baik, serta memiliki kemampuan komunikasi yang efektif dalam berinteraksi dan membangun hubungan dengan orang lain.',
                'deskripsi_kelemahan'     => 'Kamu masih ragu untuk speak up di forum besar dan cenderung menghindari konflik, yang bisa menimbulkan miskomunikasi.',
                'aktivitas_pengembangan'  => 'Latihan presentasi dan public speaking. Join diskusi aktif dalam meeting. Praktik active listening dan empati dalam komunikasi sehari-hari.',
                'rekomendasi_pelatihan'   => 'Komunikasi Efektif & Assertif | Komunikasi intrapersonal & interpersonal | Empati dalam Interaksi Profesional',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 3,
                'kode_kompetensi'         => 'TS',
                'nama_kompetensi'         => 'Thinking Skills',
                'deskripsi_kekuatan'      => 'Kamu mendorong inovasi & kreativitas, berpikir kritis, dan mampu menganalisis situasi serta informasi secara efektif untuk menciptakan solusi yang berguna.',
                'deskripsi_kelemahan'     => 'Kamu sering overthinking dan terlalu fokus pada detail, sehingga sulit ambil keputusan cepat atau menyesuaikan saat kondisi berubah.',
                'aktivitas_pengembangan'  => 'Main game strategi atau puzzle buat ngasah otak. Ikut workshop inovasi atau brainstorming kelompok. Refleksi hasil keputusan untuk evaluasi cara berpikir.',
                'rekomendasi_pelatihan'   => 'Critical Thinking & Analisis Informasi | Creative Thinking & Ideation Techniques | Problem-Solving & Decision-Making Frameworks',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 4,
                'kode_kompetensi'         => 'WWO',
                'nama_kompetensi'         => 'Work with Others',
                'deskripsi_kekuatan'      => 'Kamu cepat mengatasi masalah, mampu bekerja sama dengan orang lain untuk mencapai tujuan bersama, serta menghargai kontribusi orang lain.',
                'deskripsi_kelemahan'     => 'Kamu susah menghadapi perbedaan pendapat, terlalu mengikuti orang lain, dan kurang percaya diri ambil keputusan sendiri.',
                'aktivitas_pengembangan'  => 'Ikut proyek tim untuk melatih kolaborasi. Belajar memberi dan menerima feedback secara konstruktif. Latihan diskusi terbuka untuk menghadapi perbedaan pendapat.',
                'rekomendasi_pelatihan'   => 'Komunikasi Efektif untuk Kolaborasi dalam Tim | Empati dan Kecerdasan Emosional dalam Interaksi Tim | Team Work & Collaboration Excellence',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 5,
                'kode_kompetensi'         => 'CA',
                'nama_kompetensi'         => 'Career Attitude',
                'deskripsi_kekuatan'      => 'Kamu pantang menyerah, selalu berusaha tumbuh dan meningkatkan diri, serta memiliki ambisi tinggi untuk mencapai tujuan dan kepuasan kerja.',
                'deskripsi_kelemahan'     => 'Terlalu fokus pada diri sendiri, kadang merasa kurang puas dengan progres, dan membuat keputusan tanpa pertimbangan matang.',
                'aktivitas_pengembangan'  => 'Buat career planning dan roadmap jangka panjang. Identifikasi skill gaps dan buat plan pengembangan. Network dengan profesional di bidang yang diminati.',
                'rekomendasi_pelatihan'   => 'Etika Profesi & Integritas Personal | Proaktif & Inisiatif: Membangun Karir plan | Growth Mindset for Continuous Development',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 6,
                'kode_kompetensi'         => 'L',
                'nama_kompetensi'         => 'Leadership',
                'deskripsi_kekuatan'      => 'Kamu memiliki kemampuan untuk mempengaruhi dan membimbing orang lain, dengan ketegasan serta kemampuan analitis untuk mencapai tujuan bersama.',
                'deskripsi_kelemahan'     => 'Kamu susah mendelegasikan tugas, cenderung dominan, dan belum cukup memberi ruang tim untuk berkembang secara mandiri.',
                'aktivitas_pengembangan'  => 'Ambil peran leadership dalam proyek kecil. Ikut pelatihan public speaking dan leadership skills. Belajar dari mentor yang memiliki pengalaman kepemimpinan.',
                'rekomendasi_pelatihan'   => 'Goal Setting & Strategi Pencapaian | Komunikasi Efektif untuk Pemimpin | Influence People: Seni Membangun Pengaruh dalam Tim',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 7,
                'kode_kompetensi'         => 'SE',
                'nama_kompetensi'         => 'Self Esteem',
                'deskripsi_kekuatan'      => 'Kamu percaya diri, tegas dalam bersikap, dan terbuka pada kritik untuk terus mengembangkan diri dan memperbaiki kekurangan.',
                'deskripsi_kelemahan'     => 'Kamu kadang terlalu percaya diri hingga terlihat sombong, dan sering membandingkan diri sendiri, bikin semangat turun.',
                'aktivitas_pengembangan'  => 'Set goals yang achievable dan rayakan pencapaian kecil. Identifikasi dan kembangkan kekuatan personal. Cari feedback positif dari rekan kerja dan supervisor.',
                'rekomendasi_pelatihan'   => 'Self-Awareness & Pengenalan Diri Mendalam | Self-Compassion: Berlaku Baik pada Diri Sendiri | Positive Self-Talk & Confidence Building',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 8,
                'kode_kompetensi'         => 'PS',
                'nama_kompetensi'         => 'Problem Solving',
                'deskripsi_kekuatan'      => 'Kamu peka terhadap masalah, mampu menganalisis situasi dan memahami masalah, serta menciptakan solusi yang efektif untuk mengatasi tantangan.',
                'deskripsi_kelemahan'     => 'Kamu sering overthinking dan ragu ambil tindakan, terutama saat menghadapi masalah rumit, sehingga solusi jadi tertunda.',
                'aktivitas_pengembangan'  => 'Latihan case study dan problem solving exercises. Diskusi dengan tim untuk mendapat berbagai perspektif. Dokumentasi solusi yang berhasil untuk referensi masa depan.',
                'rekomendasi_pelatihan'   => 'Analytical Thinking & Data-Driven Decision Making | Critical Thinking for Strategic Solutions | Creative Thinking & Ideation for Breakthrough Solutions',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 9,
                'kode_kompetensi'         => 'PE',
                'nama_kompetensi'         => 'Professional Ethics',
                'deskripsi_kekuatan'      => 'Kamu berintegritas kuat, dapat membuat keputusan etis dengan pertimbangan yang matang, dan bertanggung jawab dengan tindakan yang dilakukan.',
                'deskripsi_kelemahan'     => 'Kamu kadang ragu mengambil keputusan etis saat tertekan dan kurang mempertimbangkan nilai moral dari semua sisi masalah.',
                'aktivitas_pengembangan'  => 'Pelajari code of conduct perusahaan secara mendalam. Diskusi ethical dilemmas dengan mentor atau supervisor. Refleksi personal values dan align dengan professional standards.',
                'rekomendasi_pelatihan'   => 'Kode Etik Profesi & Standar Perilaku | Hukum dan Aturan Profesional yang Relevan | Self-Awareness and Ethical Reflection',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
            [
                'id'                      => 10,
                'kode_kompetensi'         => 'GH',
                'nama_kompetensi'         => 'General Hardskills',
                'deskripsi_kekuatan'      => 'Kamu memiliki keterampilan teknis yang baik, melek teknologi, dan mampu menggunakan alat serta perangkat untuk menganalisis data atau menyelesaikan masalah.',
                'deskripsi_kelemahan'     => 'Kurang update dengan teknologi terbaru, skill teknis yang kurang mendalam, atau tidak mengikuti perkembangan industri.',
                'aktivitas_pengembangan'  => 'Ikut pelatihan teknis dan sertifikasi yang relevan. Praktik hands-on dengan tools dan software terbaru. Join community atau forum profesional untuk sharing knowledge.',
                'rekomendasi_pelatihan'   => 'Technical Skills Development | Digital Literacy & Technology Mastery | Data Analysis & Reporting Tools',
                'created_at'              => '2025-08-20 06:27:35',
                'updated_at'              => '2025-08-20 06:27:35',
            ],
        ]);
    }
}
