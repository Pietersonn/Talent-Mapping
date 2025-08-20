<?php

namespace Database\Seeders;

use App\Models\CompetencyDescription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompetencyDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competencies = [
            [
                'competency_code' => 'Self_Management',
                'competency_name' => 'Self Management',
                'strength_description' => 'Kamu peka terhadap masalah, mampu menganalisis situasi dan memahami masalah, serta menciptakan solusi yang efektif untuk mengatasi tantangan.',
                'weakness_description' => 'Terlalu fokus pada diri sendiri, kadang merasa kurang puas dengan progres, dan membuat keputusan tanpa pertimbangan matang.',
                'improvement_activity' => 'Tetapkan target jangka pendek dan panjang secara realistis. Cari mentor untuk bimbingan karier dan motivasi kerja. Evaluasi dan apresiasi progres pribadi secara berkala.'
            ],
            [
                'competency_code' => 'Thinking_Skills',
                'competency_name' => 'Thinking Skills',
                'strength_description' => 'Kemampuan berpikir kritis menganalisis informasi, mengevaluasi argumen, dan membuat keputusan yang tepat berdasarkan data yang tersedia.',
                'weakness_description' => 'Kamu sering overthinking dan terlalu fokus pada detail, sehingga sulit ambil keputusan cepat atau menyesuaikan saat kondisi berubah.',
                'improvement_activity' => 'Main game strategi atau puzzle buat ngasah otak. Ikut workshop inovasi atau brainstorming kelompok. Refleksi hasil keputusan untuk evaluasi cara berpikir.'
            ],
            [
                'competency_code' => 'Leadership',
                'competency_name' => 'Leadership',
                'strength_description' => 'Kemampuan memimpin orang lain, kemampuan untuk mengambil inisiatif, menginspirasi rekan kerja, dan menjadi teladan dalam sikap dan tindakan.',
                'weakness_description' => 'Kurang percaya diri dalam memimpin, sulit mengambil keputusan yang tegas, dan kadang menghindari tanggung jawab kepemimpinan.',
                'improvement_activity' => 'Ambil peran leadership dalam proyek kecil. Ikut pelatihan public speaking dan leadership skills. Belajar dari mentor yang memiliki pengalaman kepemimpinan.'
            ],
            [
                'competency_code' => 'Problem_Solving',
                'competency_name' => 'Problem Solving',
                'strength_description' => 'Kemampuan untuk mengidentifikasi masalah dan mencari solusi yang efektif, berpikir kreatif dan kritis dalam menghadapi berbagai situasi.',
                'weakness_description' => 'Terlalu terburu-buru dalam mencari solusi, tidak mempertimbangkan semua alternatif, atau malah overthinking sehingga lambat bertindak.',
                'improvement_activity' => 'Latihan case study dan problem solving exercises. Diskusi dengan tim untuk mendapat berbagai perspektif. Dokumentasi solusi yang berhasil untuk referensi masa depan.'
            ],
            [
                'competency_code' => 'Self_Esteem',
                'competency_name' => 'Self Esteem',
                'strength_description' => 'Rasa percaya diri untuk berinteraksi dengan baik di tempat kerja. Menghadapi tantangan dan mengambil risiko yang diperlukan dalam proses belajar.',
                'weakness_description' => 'Kurang percaya diri, sering meragukan kemampuan sendiri, takut mengambil risiko, dan cenderung menghindari tantangan.',
                'improvement_activity' => 'Set goals yang achievable dan rayakan pencapaian kecil. Identifikasi dan kembangkan kekuatan personal. Cari feedback positif dari rekan kerja dan supervisor.'
            ],
            [
                'competency_code' => 'Communication',
                'competency_name' => 'Communication & Interpersonal Ability',
                'strength_description' => 'Kemampuan berinteraksi dengan orang lain di tempat kerja, menyampaikan ide dengan jelas dan mendengarkaan dengan aktif, serta membangun hubungan yang baik dengan rekan kerja.',
                'weakness_description' => 'Sulit mengekspresikan ide dengan jelas, kurang aktif dalam diskusi, atau sebaliknya terlalu dominan dalam percakapan.',
                'improvement_activity' => 'Latihan presentasi dan public speaking. Join diskusi aktif dalam meeting. Praktik active listening dan empati dalam komunikasi sehari-hari.'
            ],
            [
                'competency_code' => 'Career_Attitude',
                'competency_name' => 'Career Attitude',
                'strength_description' => 'Sikap profesional terhadap karier mencakup motivasi, komitmen, dan etika kerja, semangat untuk belajar dan berkembang, serta memiliki pandangan positif terhadap pekerjaan mereka.',
                'weakness_description' => 'Kurang motivasi dalam pengembangan karier, tidak memiliki visi jangka panjang, atau terlalu fokus pada benefit jangka pendek.',
                'improvement_activity' => 'Buat career planning dan roadmap jangka panjang. Identifikasi skill gaps dan buat plan pengembangan. Network dengan profesional di bidang yang diminati.'
            ],
            [
                'competency_code' => 'Work_with_Others',
                'competency_name' => 'Work with Others',
                'strength_description' => 'Kemampuan untuk bekerja sama dalam tim, berkolaborasi dengan rekan kerja, menghargai perbedaan, dan berkontribusi secara positif dalam kelompok.',
                'weakness_description' => 'Kamu susah menghadapi perbedaan pendapat, terlalu mengikuti orang lain, dan kurang percaya diri ambil keputusan sendiri.',
                'improvement_activity' => 'Ikut proyek tim untuk melatih kolaborasi. Belajar memberi dan menerima feedback secara konstruktif. Latihan diskusi terbuka untuk menghadapi perbedaan pendapat.'
            ],
            [
                'competency_code' => 'Professional_Ethics',
                'competency_name' => 'Professional Ethics',
                'strength_description' => 'Etika profesional mencakup integritas, tanggung jawab, dan kejujuran dalam bekerja.',
                'weakness_description' => 'Kadang mengabaikan prosedur standar, kurang konsisten dalam menerapkan nilai-nilai etika, atau terlalu fleksibel dengan aturan.',
                'improvement_activity' => 'Pelajari code of conduct perusahaan secara mendalam. Diskusi ethical dilemmas dengan mentor atau supervisor. Refleksi personal values dan align dengan professional standards.'
            ],
            [
                'competency_code' => 'General_Hardskills',
                'competency_name' => 'General Hardskills',
                'strength_description' => 'Keterampilan teknis yang spesifik untuk bidang tertentu, seperti penguasaan perangkat lunak atau alat tertentu.',
                'weakness_description' => 'Kurang update dengan teknologi terbaru, skill teknis yang kurang mendalam, atau tidak mengikuti perkembangan industry.',
                'improvement_activity' => 'Ikut pelatihan teknis dan sertifikasi yang relevan. Praktik hands-on dengan tools dan software terbaru. Join community atau forum professional untuk sharing knowledge.'
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
