<?php

namespace Database\Seeders;

use App\Models\CompetencyDescription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompetencyDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $competencies = [
            [
                'competency_code' => 'SM',
                'competency_name' => 'Self Management',
                'strength_description' => 'Kamu bisa memanajemen waktu dengan efektif, disiplin tinggi, serta menjaga work-life balance dengan baik. Kamu juga mampu mengorganisir dan merencanakan kegiatan dengan baik untuk mencapai tujuan.',
                'weakness_description' => 'Kamu cenderung terlalu fokus sendiri, kurang terbuka berdiskusi atau meminta bantuan, dan belum cukup fleksibel dalam manajemen waktu.',
                'improvement_activity' => 'Tetapkan target jangka pendek dan panjang secara realistis. Cari mentor untuk bimbingan karier dan motivasi kerja. Evaluasi dan apresiasi progres pribadi secara berkala.'
            ],
            [
                'competency_code' => 'CIA',
                'competency_name' => 'Communication & Interpersonal Ability',
                'strength_description' => 'Kamu orang yang bertanggung jawab, pendengar yang baik, serta memiliki kemampuan komunikasi yang efektif dalam berinteraksi dan membangun hubungan dengan orang lain.',
                'weakness_description' => 'Kamu masih ragu untuk speak up di forum besar dan cenderung menghindari konflik, yang bisa menimbulkan miskomunikasi.',
                'improvement_activity' => 'Latihan presentasi dan public speaking. Join diskusi aktif dalam meeting. Praktik active listening dan empati dalam komunikasi sehari-hari.'
            ],
            [
                'competency_code' => 'TS',
                'competency_name' => 'Thinking Skills',
                'strength_description' => 'Kamu mendorong inovasi & kreativitas, berpikir kritis, dan mampu menganalisis situasi serta informasi secara efektif untuk menciptakan solusi yang berguna.',
                'weakness_description' => 'Kamu sering overthinking dan terlalu fokus pada detail, sehingga sulit ambil keputusan cepat atau menyesuaikan saat kondisi berubah.',
                'improvement_activity' => 'Main game strategi atau puzzle buat ngasah otak. Ikut workshop inovasi atau brainstorming kelompok. Refleksi hasil keputusan untuk evaluasi cara berpikir.'
            ],
            [
                'competency_code' => 'WWO',
                'competency_name' => 'Work with Others',
                'strength_description' => 'Kamu cepat mengatasi masalah, mampu bekerja sama dengan orang lain untuk mencapai tujuan bersama, serta menghargai kontribusi orang lain.',
                'weakness_description' => 'Kamu susah menghadapi perbedaan pendapat, terlalu mengikuti orang lain, dan kurang percaya diri ambil keputusan sendiri.',
                'improvement_activity' => 'Ikut proyek tim untuk melatih kolaborasi. Belajar memberi dan menerima feedback secara konstruktif. Latihan diskusi terbuka untuk menghadapi perbedaan pendapat.'
            ],
            [
                'competency_code' => 'CA',
                'competency_name' => 'Career Attitude',
                'strength_description' => 'Kamu pantang menyerah, selalu berusaha tumbuh dan meningkatkan diri, serta memiliki ambisi tinggi untuk mencapai tujuan dan kepuasan kerja.',
                'weakness_description' => 'Terlalu fokus pada diri sendiri, kadang merasa kurang puas dengan progres, dan membuat keputusan tanpa pertimbangan matang.',
                'improvement_activity' => 'Buat career planning dan roadmap jangka panjang. Identifikasi skill gaps dan buat plan pengembangan. Network dengan profesional di bidang yang diminati.'
            ],
            [
                'competency_code' => 'L',
                'competency_name' => 'Leadership',
                'strength_description' => 'Kamu memiliki kemampuan untuk mempengaruhi dan membimbing orang lain, dengan ketegasan serta kemampuan analitis untuk mencapai tujuan bersama.',
                'weakness_description' => 'Kamu susah mendelegasikan tugas, cenderung dominan, dan belum cukup memberi ruang tim untuk berkembang secara mandiri.',
                'improvement_activity' => 'Ambil peran leadership dalam proyek kecil. Ikut pelatihan public speaking dan leadership skills. Belajar dari mentor yang memiliki pengalaman kepemimpinan.'
            ],
            [
                'competency_code' => 'SE',
                'competency_name' => 'Self Esteem',
                'strength_description' => 'Kamu percaya diri, tegas dalam bersikap, dan terbuka pada kritik untuk terus mengembangkan diri dan memperbaiki kekurangan.',
                'weakness_description' => 'Kamu kadang terlalu percaya diri hingga terlihat sombong, dan sering membandingkan diri sendiri, bikin semangat turun.',
                'improvement_activity' => 'Set goals yang achievable dan rayakan pencapaian kecil. Identifikasi dan kembangkan kekuatan personal. Cari feedback positif dari rekan kerja dan supervisor.'
            ],
            [
                'competency_code' => 'PS',
                'competency_name' => 'Problem Solving',
                'strength_description' => 'Kamu peka terhadap masalah, mampu menganalisis situasi dan memahami masalah, serta menciptakan solusi yang efektif untuk mengatasi tantangan.',
                'weakness_description' => 'Kamu sering overthinking dan ragu ambil tindakan, terutama saat menghadapi masalah rumit, sehingga solusi jadi tertunda.',
                'improvement_activity' => 'Latihan case study dan problem solving exercises. Diskusi dengan tim untuk mendapat berbagai perspektif. Dokumentasi solusi yang berhasil untuk referensi masa depan.'
            ],
            [
                'competency_code' => 'PE',
                'competency_name' => 'Professional Ethics',
                'strength_description' => 'Kamu berintegritas kuat, dapat membuat keputusan etis dengan pertimbangan yang matang, dan bertanggung jawab dengan tindakan yang dilakukan.',
                'weakness_description' => 'Kamu kadang ragu mengambil keputusan etis saat tertekan dan kurang mempertimbangkan nilai moral dari semua sisi masalah.',
                'improvement_activity' => 'Pelajari code of conduct perusahaan secara mendalam. Diskusi ethical dilemmas dengan mentor atau supervisor. Refleksi personal values dan align dengan professional standards.'
            ],
            [
                'competency_code' => 'GH',
                'competency_name' => 'General Hardskills',
                'strength_description' => 'Kamu memiliki keterampilan teknis yang baik, melek teknologi, dan mampu menggunakan alat serta perangkat untuk menganalisis data atau menyelesaikan masalah.',
                'weakness_description' => 'Kurang update dengan teknologi terbaru, skill teknis yang kurang mendalam, atau tidak mengikuti perkembangan industri.',
                'improvement_activity' => 'Ikut pelatihan teknis dan sertifikasi yang relevan. Praktik hands-on dengan tools dan software terbaru. Join community atau forum profesional untuk sharing knowledge.'
            ]
        ];

        foreach ($competencies as $competency) {
            CompetencyDescription::updateOrCreate(
                ['competency_code' => $competency['competency_code']],
                $competency
            );
        }
    }
}
