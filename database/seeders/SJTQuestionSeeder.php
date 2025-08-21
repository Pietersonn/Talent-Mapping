<?php

namespace Database\Seeders;

use App\Models\SJTQuestion;
use App\Models\SJTOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SJTQuestionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $questionsWithOptions = [
                // Questions 1-5: Self Management (SM)
                [
                    'question' => [
                        'id' => 'SJ101',
                        'version_id' => 'SJV01',
                        'number' => 1,
                        'question_text' => 'Bagaimana cara kamu mengatur waktu untuk tugas yang butuh fokus tinggi?',
                        'competency' => 'SM',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ101A', 'option_letter' => 'a', 'option_text' => 'Bikin jadwal dengan jeda istirahat teratur biar fokus nggak cepat habis.', 'score' => 4],
                        ['id' => 'SJ101B', 'option_letter' => 'b', 'option_text' => 'Kerjain terus tanpa jeda biar cepat selesai, istirahat belakangan.', 'score' => 3],
                        ['id' => 'SJ101C', 'option_letter' => 'c', 'option_text' => 'Mengerjakan semuanya apa adanya biar semua selesai', 'score' => 2],
                        ['id' => 'SJ101D', 'option_letter' => 'd', 'option_text' => 'Kerjain sesuai mood, asal nggak terlalu banyak gangguan.', 'score' => 1],
                        ['id' => 'SJ101E', 'option_letter' => 'e', 'option_text' => 'Mulai kerja kalau benar-benar mood aja, nggak pengen buru-buru.', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ102',
                        'version_id' => 'SJV01',
                        'number' => 2,
                        'question_text' => 'Kalau ada situasi bikin emosi di tempat kerja, gimana caramu merespons?',
                        'competency' => 'SM',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ102A', 'option_letter' => 'a', 'option_text' => 'Menyalurkan emosi untuk melepaskan kekesalan', 'score' => 0],
                        ['id' => 'SJ102B', 'option_letter' => 'b', 'option_text' => 'Meninggalkan segera tempat agar tidak terpancing emosi', 'score' => 1],
                        ['id' => 'SJ102C', 'option_letter' => 'c', 'option_text' => 'Diam saja namun memendam emosi', 'score' => 2],
                        ['id' => 'SJ102D', 'option_letter' => 'd', 'option_text' => 'Alihkan perhatian ke hal lain dulu, baru kembali menghadapinya', 'score' => 3],
                        ['id' => 'SJ102E', 'option_letter' => 'e', 'option_text' => 'Tarik napas dalam-dalam, tenangkan diri lalu hadapi masalah dengan kepala dingin', 'score' => 4],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ103',
                        'version_id' => 'SJV01',
                        'number' => 3,
                        'question_text' => 'Kalau kamu punya banyak tugas dengan deadline yang sama, apa yang akan kamu lakukan?',
                        'competency' => 'SM',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ103A', 'option_letter' => 'a', 'option_text' => 'Kerjain semua secara bersamaan supaya semuanya kelar', 'score' => 1],
                        ['id' => 'SJ103B', 'option_letter' => 'b', 'option_text' => 'Fokus ke tugas yang paling mudah dulu', 'score' => 2],
                        ['id' => 'SJ103C', 'option_letter' => 'c', 'option_text' => 'Minta bantuan orang lain untuk menyelesaikan beberapa tugas', 'score' => 3],
                        ['id' => 'SJ103D', 'option_letter' => 'd', 'option_text' => 'Buat prioritas berdasarkan tingkat kepentingan dan deadline', 'score' => 4],
                        ['id' => 'SJ103E', 'option_letter' => 'e', 'option_text' => 'Stress dan bingung tidak tahu harus mulai dari mana', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ104',
                        'version_id' => 'SJV01',
                        'number' => 4,
                        'question_text' => 'Bagaimana cara kamu menjaga work-life balance?',
                        'competency' => 'SM',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ104A', 'option_letter' => 'a', 'option_text' => 'Bekerja terus sampai semua selesai, baru istirahat', 'score' => 1],
                        ['id' => 'SJ104B', 'option_letter' => 'b', 'option_text' => 'Tetapkan batasan waktu kerja dan waktu pribadi yang jelas', 'score' => 4],
                        ['id' => 'SJ104C', 'option_letter' => 'c', 'option_text' => 'Sesekali libur kalau sudah terlalu capek', 'score' => 2],
                        ['id' => 'SJ104D', 'option_letter' => 'd', 'option_text' => 'Fokus kerja di weekdays, weekend full untuk santai', 'score' => 3],
                        ['id' => 'SJ104E', 'option_letter' => 'e', 'option_text' => 'Tidak terlalu memikirkan balance, yang penting tugas selesai', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ105',
                        'version_id' => 'SJV01',
                        'number' => 5,
                        'question_text' => 'Ketika kamu merasa kurang motivasi untuk bekerja, apa yang kamu lakukan?',
                        'competency' => 'SM',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ105A', 'option_letter' => 'a', 'option_text' => 'Cari tahu akar masalah kurang motivasi dan atasi dari sumbernya', 'score' => 4],
                        ['id' => 'SJ105B', 'option_letter' => 'b', 'option_text' => 'Istirahat dulu sampai motivasi kembali', 'score' => 1],
                        ['id' => 'SJ105C', 'option_letter' => 'c', 'option_text' => 'Paksa diri untuk tetap bekerja meski tidak mood', 'score' => 2],
                        ['id' => 'SJ105D', 'option_letter' => 'd', 'option_text' => 'Cari inspirasi dari orang lain atau konten motivasi', 'score' => 3],
                        ['id' => 'SJ105E', 'option_letter' => 'e', 'option_text' => 'Biarkan saja sampai mood kerja datang sendiri', 'score' => 0],
                    ]
                ],

                // Questions 6-10: Communication & Interpersonal Ability (CIA)
                [
                    'question' => [
                        'id' => 'SJ106',
                        'version_id' => 'SJV01',
                        'number' => 6,
                        'question_text' => 'Bagaimana cara kamu berkomunikasi dengan rekan kerja yang memiliki pendapat berbeda?',
                        'competency' => 'CIA',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ106A', 'option_letter' => 'a', 'option_text' => 'Dengarkan dengan baik, lalu sampaikan pendapat dengan sopan dan berikan alasan', 'score' => 4],
                        ['id' => 'SJ106B', 'option_letter' => 'b', 'option_text' => 'Hindari diskusi untuk mencegah konflik', 'score' => 1],
                        ['id' => 'SJ106C', 'option_letter' => 'c', 'option_text' => 'Tetap pada pendapat sendiri tanpa mau mendengar yang lain', 'score' => 0],
                        ['id' => 'SJ106D', 'option_letter' => 'd', 'option_text' => 'Ikuti saja pendapat mayoritas untuk menghindari masalah', 'score' => 2],
                        ['id' => 'SJ106E', 'option_letter' => 'e', 'option_text' => 'Cari titik temu dan solusi yang bisa diterima bersama', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ107',
                        'version_id' => 'SJV01',
                        'number' => 7,
                        'question_text' => 'Ketika presentasi, bagaimana cara kamu memastikan audiens memahami materi?',
                        'competency' => 'CIA',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ107A', 'option_letter' => 'a', 'option_text' => 'Baca slide saja agar tidak ada yang terlewat', 'score' => 1],
                        ['id' => 'SJ107B', 'option_letter' => 'b', 'option_text' => 'Gunakan bahasa sederhana dan berikan contoh konkret', 'score' => 4],
                        ['id' => 'SJ107C', 'option_letter' => 'c', 'option_text' => 'Bicara cepat agar semua materi tersampaikan', 'score' => 0],
                        ['id' => 'SJ107D', 'option_letter' => 'd', 'option_text' => 'Sesekali tanya apakah ada yang kurang jelas', 'score' => 3],
                        ['id' => 'SJ107E', 'option_letter' => 'e', 'option_text' => 'Fokus pada slide dan hindari kontak mata', 'score' => 2],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ108',
                        'version_id' => 'SJV01',
                        'number' => 8,
                        'question_text' => 'Bagaimana cara kamu memberikan feedback kepada rekan kerja?',
                        'competency' => 'CIA',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ108A', 'option_letter' => 'a', 'option_text' => 'Langsung to the point tanpa basa-basi', 'score' => 2],
                        ['id' => 'SJ108B', 'option_letter' => 'b', 'option_text' => 'Mulai dengan hal positif, lalu sampaikan area perbaikan secara konstruktif', 'score' => 4],
                        ['id' => 'SJ108C', 'option_letter' => 'c', 'option_text' => 'Hindari memberikan feedback negatif agar tidak menyinggung', 'score' => 1],
                        ['id' => 'SJ108D', 'option_letter' => 'd', 'option_text' => 'Sampaikan melalui orang lain agar tidak awkward', 'score' => 0],
                        ['id' => 'SJ108E', 'option_letter' => 'e', 'option_text' => 'Pilih waktu dan tempat yang tepat untuk berbicara empat mata', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ109',
                        'version_id' => 'SJV01',
                        'number' => 9,
                        'question_text' => 'Ketika ada konflik antar tim, bagaimana sikapmu?',
                        'competency' => 'CIA',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ109A', 'option_letter' => 'a', 'option_text' => 'Hindari terlibat agar tidak ikut bermasalah', 'score' => 1],
                        ['id' => 'SJ109B', 'option_letter' => 'b', 'option_text' => 'Dukung pihak yang menurutku paling benar', 'score' => 2],
                        ['id' => 'SJ109C', 'option_letter' => 'c', 'option_text' => 'Coba jadi penengah dan bantu cari solusi bersama', 'score' => 4],
                        ['id' => 'SJ109D', 'option_letter' => 'd', 'option_text' => 'Laporkan ke atasan agar mereka yang menyelesaikan', 'score' => 3],
                        ['id' => 'SJ109E', 'option_letter' => 'e', 'option_text' => 'Biarkan saja, nanti juga reda sendiri', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ110',
                        'version_id' => 'SJV01',
                        'number' => 10,
                        'question_text' => 'Bagaimana cara kamu membangun hubungan baik dengan klien baru?',
                        'competency' => 'CIA',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ110A', 'option_letter' => 'a', 'option_text' => 'Langsung fokus ke urusan bisnis tanpa small talk', 'score' => 2],
                        ['id' => 'SJ110B', 'option_letter' => 'b', 'option_text' => 'Pelajari dulu background dan kebutuhan mereka', 'score' => 4],
                        ['id' => 'SJ110C', 'option_letter' => 'c', 'option_text' => 'Tunggu mereka yang memulai pembicaraan', 'score' => 1],
                        ['id' => 'SJ110D', 'option_letter' => 'd', 'option_text' => 'Bersikap ramah dan terbuka untuk membangun trust', 'score' => 3],
                        ['id' => 'SJ110E', 'option_letter' => 'e', 'option_text' => 'Ikuti saja arahan atasan untuk pendekatan ke klien', 'score' => 0],
                    ]
                ],

                // Questions 11-15: Thinking Skills (TS)
                [
                    'question' => [
                        'id' => 'SJ111',
                        'version_id' => 'SJV01',
                        'number' => 11,
                        'question_text' => 'Ketika menghadapi masalah kompleks, langkah pertama yang kamu lakukan?',
                        'competency' => 'TS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ111A', 'option_letter' => 'a', 'option_text' => 'Langsung cari solusi tercepat', 'score' => 1],
                        ['id' => 'SJ111B', 'option_letter' => 'b', 'option_text' => 'Pecah masalah jadi bagian-bagian kecil yang lebih mudah ditangani', 'score' => 4],
                        ['id' => 'SJ111C', 'option_letter' => 'c', 'option_text' => 'Kumpulkan semua informasi yang tersedia terlebih dahulu', 'score' => 3],
                        ['id' => 'SJ111D', 'option_letter' => 'd', 'option_text' => 'Minta bantuan orang yang lebih berpengalaman', 'score' => 2],
                        ['id' => 'SJ111E', 'option_letter' => 'e', 'option_text' => 'Bingung dan tidak tahu harus mulai dari mana', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ112',
                        'version_id' => 'SJV01',
                        'number' => 12,
                        'question_text' => 'Bagaimana cara kamu mengevaluasi informasi yang kamu terima?',
                        'competency' => 'TS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ112A', 'option_letter' => 'a', 'option_text' => 'Terima saja kalau dari sumber terpercaya', 'score' => 2],
                        ['id' => 'SJ112B', 'option_letter' => 'b', 'option_text' => 'Cek kebenaran dari berbagai sumber lain', 'score' => 4],
                        ['id' => 'SJ112C', 'option_letter' => 'c', 'option_text' => 'Analisis apakah masuk akal atau tidak', 'score' => 3],
                        ['id' => 'SJ112D', 'option_letter' => 'd', 'option_text' => 'Langsung gunakan tanpa cek ulang', 'score' => 0],
                        ['id' => 'SJ112E', 'option_letter' => 'e', 'option_text' => 'Tanya pendapat orang lain dulu', 'score' => 1],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ113',
                        'version_id' => 'SJV01',
                        'number' => 13,
                        'question_text' => 'Ketika harus membuat keputusan penting dengan data terbatas, apa yang kamu lakukan?',
                        'competency' => 'TS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ113A', 'option_letter' => 'a', 'option_text' => 'Tunggu sampai data lengkap baru ambil keputusan', 'score' => 2],
                        ['id' => 'SJ113B', 'option_letter' => 'b', 'option_text' => 'Gunakan data yang ada, analisis risiko, dan buat keputusan terbaik', 'score' => 4],
                        ['id' => 'SJ113C', 'option_letter' => 'c', 'option_text' => 'Ikuti feeling dan pengalaman sebelumnya', 'score' => 1],
                        ['id' => 'SJ113D', 'option_letter' => 'd', 'option_text' => 'Minta orang lain yang memutuskan', 'score' => 0],
                        ['id' => 'SJ113E', 'option_letter' => 'e', 'option_text' => 'Cari tambahan data sebanyak mungkin dalam waktu terbatas', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ114',
                        'version_id' => 'SJV01',
                        'number' => 14,
                        'question_text' => 'Bagaimana cara kamu mengembangkan ide kreatif untuk proyek?',
                        'competency' => 'TS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ114A', 'option_letter' => 'a', 'option_text' => 'Brainstorming dengan tim untuk dapat banyak perspektif', 'score' => 4],
                        ['id' => 'SJ114B', 'option_letter' => 'b', 'option_text' => 'Cari referensi dari proyek serupa yang sudah ada', 'score' => 3],
                        ['id' => 'SJ114C', 'option_letter' => 'c', 'option_text' => 'Tunggu inspirasi datang sendiri', 'score' => 1],
                        ['id' => 'SJ114D', 'option_letter' => 'd', 'option_text' => 'Ikuti template yang sudah terbukti berhasil', 'score' => 2],
                        ['id' => 'SJ114E', 'option_letter' => 'e', 'option_text' => 'Tidak perlu terlalu kreatif, yang penting selesai', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ115',
                        'version_id' => 'SJV01',
                        'number' => 15,
                        'question_text' => 'Ketika menganalisis suatu masalah, pendekatan apa yang kamu gunakan?',
                        'competency' => 'TS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ115A', 'option_letter' => 'a', 'option_text' => 'Fokus pada detail-detail kecil terlebih dahulu', 'score' => 2],
                        ['id' => 'SJ115B', 'option_letter' => 'b', 'option_text' => 'Lihat gambaran besar dulu, baru ke detail', 'score' => 4],
                        ['id' => 'SJ115C', 'option_letter' => 'c', 'option_text' => 'Ikuti cara yang biasa digunakan', 'score' => 1],
                        ['id' => 'SJ115D', 'option_letter' => 'd', 'option_text' => 'Cari pola atau kesamaan dengan masalah sebelumnya', 'score' => 3],
                        ['id' => 'SJ115E', 'option_letter' => 'e', 'option_text' => 'Langsung cari solusi tanpa analisis mendalam', 'score' => 0],
                    ]
                ],

                // Questions 16-20: Work with Others (WWO)
                [
                    'question' => [
                        'id' => 'SJ116',
                        'version_id' => 'SJV01',
                        'number' => 16,
                        'question_text' => 'Ketika bekerja dalam tim, bagaimana cara kamu berkontribusi?',
                        'competency' => 'WWO',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ116A', 'option_letter' => 'a', 'option_text' => 'Ambil peran sesuai keahlian dan dukung anggota tim lainnya', 'score' => 4],
                        ['id' => 'SJ116B', 'option_letter' => 'b', 'option_text' => 'Fokus pada tugas sendiri agar tidak mengganggu yang lain', 'score' => 2],
                        ['id' => 'SJ116C', 'option_letter' => 'c', 'option_text' => 'Ikuti arahan ketua tim tanpa banyak inisiatif', 'score' => 1],
                        ['id' => 'SJ116D', 'option_letter' => 'd', 'option_text' => 'Bantu tim mencapai tujuan bersama dengan komunikasi aktif', 'score' => 3],
                        ['id' => 'SJ116E', 'option_letter' => 'e', 'option_text' => 'Kerja minimal asal tugas selesai', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ117',
                        'version_id' => 'SJV01',
                        'number' => 17,
                        'question_text' => 'Bagaimana sikapmu ketika ada anggota tim yang tidak pull weight-nya?',
                        'competency' => 'WWO',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ117A', 'option_letter' => 'a', 'option_text' => 'Langsung komplain ke atasan', 'score' => 1],
                        ['id' => 'SJ117B', 'option_letter' => 'b', 'option_text' => 'Ambil alih tugasnya agar target tercapai', 'score' => 2],
                        ['id' => 'SJ117C', 'option_letter' => 'c', 'option_text' => 'Ajak bicara empat mata untuk memahami masalahnya', 'score' => 4],
                        ['id' => 'SJ117D', 'option_letter' => 'd', 'option_text' => 'Biarkan saja, bukan urusan saya', 'score' => 0],
                        ['id' => 'SJ117E', 'option_letter' => 'e', 'option_text' => 'Tawarkan bantuan atau support yang dibutuhkan', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ118',
                        'version_id' => 'SJV01',
                        'number' => 18,
                        'question_text' => 'Ketika ada perbedaan pendapat dalam tim, bagaimana kamu menyikapinya?',
                        'competency' => 'WWO',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ118A', 'option_letter' => 'a', 'option_text' => 'Hindari konflik dengan diam saja', 'score' => 1],
                        ['id' => 'SJ118B', 'option_letter' => 'b', 'option_text' => 'Pertahankan pendapat sendiri dengan tegas', 'score' => 2],
                        ['id' => 'SJ118C', 'option_letter' => 'c', 'option_text' => 'Dengarkan semua pendapat dan cari solusi kompromi terbaik', 'score' => 4],
                        ['id' => 'SJ118D', 'option_letter' => 'd', 'option_text' => 'Ikuti suara mayoritas', 'score' => 3],
                        ['id' => 'SJ118E', 'option_letter' => 'e', 'option_text' => 'Biarkan orang lain yang memutuskan', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ119',
                        'version_id' => 'SJV01',
                        'number' => 19,
                        'question_text' => 'Bagaimana cara kamu membangun kepercayaan dalam tim?',
                        'competency' => 'WWO',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ119A', 'option_letter' => 'a', 'option_text' => 'Selalu tepati janji dan komitmen yang dibuat', 'score' => 4],
                        ['id' => 'SJ119B', 'option_letter' => 'b', 'option_text' => 'Bersikap terbuka dan jujur dalam komunikasi', 'score' => 3],
                        ['id' => 'SJ119C', 'option_letter' => 'c', 'option_text' => 'Fokus pada hasil kerja yang berkualitas', 'score' => 2],
                        ['id' => 'SJ119D', 'option_letter' => 'd', 'option_text' => 'Tunggu waktu sampai kepercayaan terbentuk sendiri', 'score' => 1],
                        ['id' => 'SJ119E', 'option_letter' => 'e', 'option_text' => 'Tidak terlalu memikirkan masalah kepercayaan', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ120',
                        'version_id' => 'SJV01',
                        'number' => 20,
                        'question_text' => 'Ketika harus memberikan kritik konstruktif kepada rekan tim, bagaimana caramu?',
                        'competency' => 'WWO',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ120A', 'option_letter' => 'a', 'option_text' => 'Langsung sampaikan apa yang salah', 'score' => 1],
                        ['id' => 'SJ120B', 'option_letter' => 'b', 'option_text' => 'Hindari memberikan kritik agar tidak menyinggung', 'score' => 0],
                        ['id' => 'SJ120C', 'option_letter' => 'c', 'option_text' => 'Sampaikan dengan empati, fokus pada perilaku bukan orangnya', 'score' => 4],
                        ['id' => 'SJ120D', 'option_letter' => 'd', 'option_text' => 'Berikan kritik lewat pesan tertulis', 'score' => 2],
                        ['id' => 'SJ120E', 'option_letter' => 'e', 'option_text' => 'Pilih waktu dan tempat yang tepat untuk berbicara', 'score' => 3],
                    ]
                ],

                // Questions 21-25: Career Attitude (CA)
                [
                    'question' => [
                        'id' => 'SJ121',
                        'version_id' => 'SJV01',
                        'number' => 21,
                        'question_text' => 'Bagaimana sikapmu terhadap target dan deadline kerja?',
                        'competency' => 'CA',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ121A', 'option_letter' => 'a', 'option_text' => 'Berusaha mencapai target sebaik mungkin dalam deadline', 'score' => 4],
                        ['id' => 'SJ121B', 'option_letter' => 'b', 'option_text' => 'Kerjakan apa adanya asal selesai tepat waktu', 'score' => 2],
                        ['id' => 'SJ121C', 'option_letter' => 'c', 'option_text' => 'Sering telat tapi hasil lebih berkualitas', 'score' => 1],
                        ['id' => 'SJ121D', 'option_letter' => 'd', 'option_text' => 'Fokus pada kualitas, deadline bisa dinego', 'score' => 3],
                        ['id' => 'SJ121E', 'option_letter' => 'e', 'option_text' => 'Tidak terlalu stress dengan target, santai saja', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ122',
                        'version_id' => 'SJV01',
                        'number' => 22,
                        'question_text' => 'Bagaimana cara kamu mengembangkan diri dalam karir?',
                        'competency' => 'CA',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ122A', 'option_letter' => 'a', 'option_text' => 'Aktif ikut training dan upgrade skill sesuai kebutuhan industri', 'score' => 4],
                        ['id' => 'SJ122B', 'option_letter' => 'b', 'option_text' => 'Fokus pada pekerjaan sekarang dulu', 'score' => 2],
                        ['id' => 'SJ122C', 'option_letter' => 'c', 'option_text' => 'Ikut training kalau disuruh perusahaan', 'score' => 1],
                        ['id' => 'SJ122D', 'option_letter' => 'd', 'option_text' => 'Belajar dari pengalaman kerja sehari-hari', 'score' => 3],
                        ['id' => 'SJ122E', 'option_letter' => 'e', 'option_text' => 'Tidak terlalu memikirkan pengembangan diri', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ123',
                        'version_id' => 'SJV01',
                        'number' => 23,
                        'question_text' => 'Ketika menghadapi tantangan baru di pekerjaan, sikapmu adalah?',
                        'competency' => 'CA',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ123A', 'option_letter' => 'a', 'option_text' => 'Excited dan siap belajar hal baru', 'score' => 4],
                        ['id' => 'SJ123B', 'option_letter' => 'b', 'option_text' => 'Worried tapi tetap coba hadapi', 'score' => 3],
                        ['id' => 'SJ123C', 'option_letter' => 'c', 'option_text' => 'Hindari kalau bisa, prefer zona nyaman', 'score' => 1],
                        ['id' => 'SJ123D', 'option_letter' => 'd', 'option_text' => 'Ikuti aja arahan atasan', 'score' => 2],
                        ['id' => 'SJ123E', 'option_letter' => 'e', 'option_text' => 'Stress dan berharap ada orang lain yang handle', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ124',
                        'version_id' => 'SJV01',
                        'number' => 24,
                        'question_text' => 'Bagaimana cara kamu menjaga motivasi kerja jangka panjang?',
                        'competency' => 'CA',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ124A', 'option_letter' => 'a', 'option_text' => 'Set goals pribadi dan track progress pencapaian', 'score' => 4],
                        ['id' => 'SJ124B', 'option_letter' => 'b', 'option_text' => 'Fokus pada reward dan benefit yang didapat', 'score' => 2],
                        ['id' => 'SJ124C', 'option_letter' => 'c', 'option_text' => 'Cari meaning dan purpose dari pekerjaan yang dilakukan', 'score' => 3],
                        ['id' => 'SJ124D', 'option_letter' => 'd', 'option_text' => 'Bergantung pada mood dan situasi', 'score' => 1],
                        ['id' => 'SJ124E', 'option_letter' => 'e', 'option_text' => 'Tidak terlalu memikirkan motivasi jangka panjang', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ125',
                        'version_id' => 'SJV01',
                        'number' => 25,
                        'question_text' => 'Ketika ada kesempatan promosi, bagaimana sikapmu?',
                        'competency' => 'CA',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ125A', 'option_letter' => 'a', 'option_text' => 'Aktif mempersiapkan diri dan apply dengan percaya diri', 'score' => 4],
                        ['id' => 'SJ125B', 'option_letter' => 'b', 'option_text' => 'Tunggu ditawari aja', 'score' => 1],
                        ['id' => 'SJ125C', 'option_letter' => 'c', 'option_text' => 'Pertimbangkan kesiapan dan kemampuan dulu', 'score' => 3],
                        ['id' => 'SJ125D', 'option_letter' => 'd', 'option_text' => 'Prefer tetap di posisi sekarang yang sudah nyaman', 'score' => 2],
                        ['id' => 'SJ125E', 'option_letter' => 'e', 'option_text' => 'Tidak tertarik dengan promosi dan tanggung jawab tambahan', 'score' => 0],
                    ]
                ],

                // Questions 26-30: Leadership (L)
                [
                    'question' => [
                        'id' => 'SJ126',
                        'version_id' => 'SJV01',
                        'number' => 26,
                        'question_text' => 'Ketika diberi tanggung jawab memimpin tim, pendekatan apa yang kamu gunakan?',
                        'competency' => 'L',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ126A', 'option_letter' => 'a', 'option_text' => 'Berikan arahan jelas dan monitor progress secara rutin', 'score' => 4],
                        ['id' => 'SJ126B', 'option_letter' => 'b', 'option_text' => 'Biarkan tim bekerja mandiri tanpa terlalu banyak intervensi', 'score' => 2],
                        ['id' => 'SJ126C', 'option_letter' => 'c', 'option_text' => 'Fokus pada hasil akhir, proses terserah tim', 'score' => 1],
                        ['id' => 'SJ126D', 'option_letter' => 'd', 'option_text' => 'Bangun komunikasi dua arah dan dukung pengembangan anggota tim', 'score' => 3],
                        ['id' => 'SJ126E', 'option_letter' => 'e', 'option_text' => 'Nervous dan prefer tidak ambil peran leadership', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ127',
                        'version_id' => 'SJV01',
                        'number' => 27,
                        'question_text' => 'Bagaimana cara kamu mengatasi anggota tim yang resistant terhadap perubahan?',
                        'competency' => 'L',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ127A', 'option_letter' => 'a', 'option_text' => 'Paksa mereka untuk mengikuti perubahan', 'score' => 1],
                        ['id' => 'SJ127B', 'option_letter' => 'b', 'option_text' => 'Jelaskan benefit dan alasan perubahan dengan sabar', 'score' => 4],
                        ['id' => 'SJ127C', 'option_letter' => 'c', 'option_text' => 'Biarkan saja, nanti juga terbiasa sendiri', 'score' => 0],
                        ['id' => 'SJ127D', 'option_letter' => 'd', 'option_text' => 'Involve mereka dalam proses planning perubahan', 'score' => 3],
                        ['id' => 'SJ127E', 'option_letter' => 'e', 'option_text' => 'Berikan support dan training yang dibutuhkan', 'score' => 2],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ128',
                        'version_id' => 'SJV01',
                        'number' => 28,
                        'question_text' => 'Ketika tim menghadapi deadline ketat, bagaimana cara kamu memotivasi mereka?',
                        'competency' => 'L',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ128A', 'option_letter' => 'a', 'option_text' => 'Pressure semua orang untuk bekerja lebih keras', 'score' => 1],
                        ['id' => 'SJ128B', 'option_letter' => 'b', 'option_text' => 'Bantu identifikasi prioritas dan realokasi resource', 'score' => 4],
                        ['id' => 'SJ128C', 'option_letter' => 'c', 'option_text' => 'Biarkan tim figure out sendiri', 'score' => 0],
                        ['id' => 'SJ128D', 'option_letter' => 'd', 'option_text' => 'Berikan support dan remove obstacle yang menghambat', 'score' => 3],
                        ['id' => 'SJ128E', 'option_letter' => 'e', 'option_text' => 'Ingatkan tentang konsekuensi kalau tidak selesai tepat waktu', 'score' => 2],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ129',
                        'version_id' => 'SJV01',
                        'number' => 29,
                        'question_text' => 'Bagaimana cara kamu menangani konflik antar anggota tim?',
                        'competency' => 'L',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ129A', 'option_letter' => 'a', 'option_text' => 'Langsung putuskan siapa yang benar dan salah', 'score' => 1],
                        ['id' => 'SJ129B', 'option_letter' => 'b', 'option_text' => 'Biarkan mereka selesaikan sendiri', 'score' => 0],
                        ['id' => 'SJ129C', 'option_letter' => 'c', 'option_text' => 'Fasilitasi diskusi untuk cari solusi win-win', 'score' => 4],
                        ['id' => 'SJ129D', 'option_letter' => 'd', 'option_text' => 'Pisahkan mereka ke tugas yang berbeda', 'score' => 2],
                        ['id' => 'SJ129E', 'option_letter' => 'e', 'option_text' => 'Dengarkan kedua pihak dan cari jalan tengah', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ130',
                        'version_id' => 'SJV01',
                        'number' => 30,
                        'question_text' => 'Bagaimana cara kamu mengembangkan potensi anggota tim?',
                        'competency' => 'L',
                        'page_number' => 1,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ130A', 'option_letter' => 'a', 'option_text' => 'Berikan tugas yang challenging untuk growth', 'score' => 4],
                        ['id' => 'SJ130B', 'option_letter' => 'b', 'option_text' => 'Fokus pada hasil kerja, pengembangan bukan prioritas', 'score' => 1],
                        ['id' => 'SJ130C', 'option_letter' => 'c', 'option_text' => 'Berikan feedback regular dan coaching', 'score' => 3],
                        ['id' => 'SJ130D', 'option_letter' => 'd', 'option_text' => 'Biarkan mereka develop naturally', 'score' => 0],
                        ['id' => 'SJ130E', 'option_letter' => 'e', 'option_text' => 'Identifikasi strength dan berikan kesempatan sesuai potensi', 'score' => 2],
                    ]
                ],

                // Questions 31-35: Self Esteem (SE)
                [
                    'question' => [
                        'id' => 'SJ131',
                        'version_id' => 'SJV01',
                        'number' => 31,
                        'question_text' => 'Ketika mendapat kritik dari atasan, bagaimana reaksimu?',
                        'competency' => 'SE',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ131A', 'option_letter' => 'a', 'option_text' => 'Defensive dan cari alasan untuk membela diri', 'score' => 1],
                        ['id' => 'SJ131B', 'option_letter' => 'b', 'option_text' => 'Dengarkan dengan baik dan terima sebagai bahan perbaikan', 'score' => 4],
                        ['id' => 'SJ131C', 'option_letter' => 'c', 'option_text' => 'Merasa down dan kehilangan motivasi', 'score' => 0],
                        ['id' => 'SJ131D', 'option_letter' => 'd', 'option_text' => 'Tanya detail untuk memahami area yang perlu diperbaiki', 'score' => 3],
                        ['id' => 'SJ131E', 'option_letter' => 'e', 'option_text' => 'Terima begitu saja tanpa banyak respons', 'score' => 2],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ132',
                        'version_id' => 'SJV01',
                        'number' => 32,
                        'question_text' => 'Bagaimana sikapmu ketika harus presentasi di depan banyak orang?',
                        'competency' => 'SE',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ132A', 'option_letter' => 'a', 'option_text' => 'Nervous tapi tetap lakukan yang terbaik', 'score' => 3],
                        ['id' => 'SJ132B', 'option_letter' => 'b', 'option_text' => 'Confident dan enjoy prosesnya', 'score' => 4],
                        ['id' => 'SJ132C', 'option_letter' => 'c', 'option_text' => 'Hindari kalau bisa atau minta orang lain yang presentasi', 'score' => 0],
                        ['id' => 'SJ132D', 'option_letter' => 'd', 'option_text' => 'Persiapan extra agar lebih percaya diri', 'score' => 2],
                        ['id' => 'SJ132E', 'option_letter' => 'e', 'option_text' => 'Cemas berlebihan sampai mengganggu performa', 'score' => 1],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ133',
                        'version_id' => 'SJV01',
                        'number' => 33,
                        'question_text' => 'Ketika melakukan kesalahan di tempat kerja, apa yang kamu lakukan?',
                        'competency' => 'SE',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ133A', 'option_letter' => 'a', 'option_text' => 'Langsung akui kesalahan dan cari solusi perbaikan', 'score' => 4],
                        ['id' => 'SJ133B', 'option_letter' => 'b', 'option_text' => 'Coba tutupi agar tidak ketahuan', 'score' => 0],
                        ['id' => 'SJ133C', 'option_letter' => 'c', 'option_text' => 'Cari alasan atau kambing hitam', 'score' => 1],
                        ['id' => 'SJ133D', 'option_letter' => 'd', 'option_text' => 'Merasa bersalah berlebihan dan self-blame', 'score' => 2],
                        ['id' => 'SJ133E', 'option_letter' => 'e', 'option_text' => 'Akui kesalahan dan belajar agar tidak terulang', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ134',
                        'version_id' => 'SJV01',
                        'number' => 34,
                        'question_text' => 'Bagaimana cara kamu menghadapi kompetisi dengan rekan kerja?',
                        'competency' => 'SE',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ134A', 'option_letter' => 'a', 'option_text' => 'Lihat sebagai motivasi untuk improve diri', 'score' => 4],
                        ['id' => 'SJ134B', 'option_letter' => 'b', 'option_text' => 'Merasa terancam dan defensif', 'score' => 1],
                        ['id' => 'SJ134C', 'option_letter' => 'c', 'option_text' => 'Fokus pada performa sendiri tanpa membandingkan', 'score' => 3],
                        ['id' => 'SJ134D', 'option_letter' => 'd', 'option_text' => 'Hindari kompetisi dan cari area lain', 'score' => 0],
                        ['id' => 'SJ134E', 'option_letter' => 'e', 'option_text' => 'Stress dan overthinking tentang performa orang lain', 'score' => 2],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ135',
                        'version_id' => 'SJV01',
                        'number' => 35,
                        'question_text' => 'Ketika diberi tanggung jawab baru yang menantang, reaksimu adalah?',
                        'competency' => 'SE',
                        'page_number' => 2,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ135A', 'option_letter' => 'a', 'option_text' => 'Excited karena bisa belajar dan berkembang', 'score' => 4],
                        ['id' => 'SJ135B', 'option_letter' => 'b', 'option_text' => 'Takut gagal dan tidak mampu', 'score' => 1],
                        ['id' => 'SJ135C', 'option_letter' => 'c', 'option_text' => 'Terima tapi dengan perasaan cemas', 'score' => 2],
                        ['id' => 'SJ135D', 'option_letter' => 'd', 'option_text' => 'Confident bisa handle dengan baik', 'score' => 3],
                        ['id' => 'SJ135E', 'option_letter' => 'e', 'option_text' => 'Tolak karena di luar comfort zone', 'score' => 0],
                    ]
                ],

                // Questions 36-40: Problem Solving (PS)
                [
                    'question' => [
                        'id' => 'SJ136',
                        'version_id' => 'SJV01',
                        'number' => 36,
                        'question_text' => 'Ketika menghadapi masalah yang belum pernah dihadapi sebelumnya, apa langkah pertamamu?',
                        'competency' => 'PS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ136A', 'option_letter' => 'a', 'option_text' => 'Analisis masalah secara menyeluruh dan cari pola yang mirip', 'score' => 4],
                        ['id' => 'SJ136B', 'option_letter' => 'b', 'option_text' => 'Langsung coba berbagai solusi sampai ada yang berhasil', 'score' => 2],
                        ['id' => 'SJ136C', 'option_letter' => 'c', 'option_text' => 'Minta bantuan orang yang lebih berpengalaman', 'score' => 3],
                        ['id' => 'SJ136D', 'option_letter' => 'd', 'option_text' => 'Bingung dan tidak tahu harus mulai dari mana', 'score' => 0],
                        ['id' => 'SJ136E', 'option_letter' => 'e', 'option_text' => 'Cari referensi dan best practices dari sumber lain', 'score' => 1],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ137',
                        'version_id' => 'SJV01',
                        'number' => 37,
                        'question_text' => 'Bagaimana cara kamu mengevaluasi efektivitas solusi yang sudah diterapkan?',
                        'competency' => 'PS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ137A', 'option_letter' => 'a', 'option_text' => 'Set metrics yang jelas dan monitor hasilnya secara berkala', 'score' => 4],
                        ['id' => 'SJ137B', 'option_letter' => 'b', 'option_text' => 'Lihat apakah masalah masih muncul atau tidak', 'score' => 2],
                        ['id' => 'SJ137C', 'option_letter' => 'c', 'option_text' => 'Tidak terlalu memikirkan evaluasi, fokus ke masalah berikutnya', 'score' => 0],
                        ['id' => 'SJ137D', 'option_letter' => 'd', 'option_text' => 'Minta feedback dari stakeholder yang terdampak', 'score' => 3],
                        ['id' => 'SJ137E', 'option_letter' => 'e', 'option_text' => 'Tunggu complaint atau masalah baru muncul', 'score' => 1],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ138',
                        'version_id' => 'SJV01',
                        'number' => 38,
                        'question_text' => 'Ketika solusi pertama tidak berhasil, apa yang kamu lakukan?',
                        'competency' => 'PS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ138A', 'option_letter' => 'a', 'option_text' => 'Analisis kenapa gagal dan coba pendekatan berbeda', 'score' => 4],
                        ['id' => 'SJ138B', 'option_letter' => 'b', 'option_text' => 'Frustrasi dan menyerah sementara', 'score' => 0],
                        ['id' => 'SJ138C', 'option_letter' => 'c', 'option_text' => 'Ulangi solusi yang sama dengan cara yang berbeda', 'score' => 2],
                        ['id' => 'SJ138D', 'option_letter' => 'd', 'option_text' => 'Brainstorming dengan tim untuk dapat perspektif baru', 'score' => 3],
                        ['id' => 'SJ138E', 'option_letter' => 'e', 'option_text' => 'Escalate ke atasan untuk dihandle', 'score' => 1],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ139',
                        'version_id' => 'SJV01',
                        'number' => 39,
                        'question_text' => 'Bagaimana cara kamu memprioritaskan masalah ketika ada beberapa issue yang harus diselesaikan bersamaan?',
                        'competency' => 'PS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ139A', 'option_letter' => 'a', 'option_text' => 'Selesaikan yang paling mudah dulu untuk quick wins', 'score' => 2],
                        ['id' => 'SJ139B', 'option_letter' => 'b', 'option_text' => 'Prioritas berdasarkan dampak dan urgency menggunakan matrix', 'score' => 4],
                        ['id' => 'SJ139C', 'option_letter' => 'c', 'option_text' => 'Tangani semua secara bersamaan', 'score' => 0],
                        ['id' => 'SJ139D', 'option_letter' => 'd', 'option_text' => 'Fokus pada masalah yang paling mendesak dulu', 'score' => 3],
                        ['id' => 'SJ139E', 'option_letter' => 'e', 'option_text' => 'Ikuti instruksi atasan untuk prioritas', 'score' => 1],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ140',
                        'version_id' => 'SJV01',
                        'number' => 40,
                        'question_text' => 'Ketika harus menjelaskan solusi kompleks kepada non-technical stakeholder, bagaimana caramu?',
                        'competency' => 'PS',
                        'page_number' => 3,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ140A', 'option_letter' => 'a', 'option_text' => 'Gunakan analogi dan contoh yang mudah dipahami', 'score' => 4],
                        ['id' => 'SJ140B', 'option_letter' => 'b', 'option_text' => 'Jelaskan dengan detail teknis agar lengkap', 'score' => 1],
                        ['id' => 'SJ140C', 'option_letter' => 'c', 'option_text' => 'Buat visual diagram dan flowchart', 'score' => 3],
                        ['id' => 'SJ140D', 'option_letter' => 'd', 'option_text' => 'Fokus pada benefit dan outcome daripada cara kerja', 'score' => 2],
                        ['id' => 'SJ140E', 'option_letter' => 'e', 'option_text' => 'Minta technical lead lain yang menjelaskan', 'score' => 0],
                    ]
                ],

                // Questions 41-45: Professional Ethics (PE)
                [
                    'question' => [
                        'id' => 'SJ141',
                        'version_id' => 'SJV01',
                        'number' => 41,
                        'question_text' => 'Ketika melihat rekan kerja melakukan sesuatu yang melanggar aturan perusahaan, apa yang kamu lakukan?',
                        'competency' => 'PE',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ141A', 'option_letter' => 'a', 'option_text' => 'Tegur secara pribadi dan beri kesempatan untuk memperbaiki', 'score' => 4],
                        ['id' => 'SJ141B', 'option_letter' => 'b', 'option_text' => 'Biarkan saja, bukan urusan saya', 'score' => 0],
                        ['id' => 'SJ141C', 'option_letter' => 'c', 'option_text' => 'Langsung laporkan ke atasan', 'score' => 2],
                        ['id' => 'SJ141D', 'option_letter' => 'd', 'option_text' => 'Diskusi dengan rekan lain dulu sebelum bertindak', 'score' => 1],
                        ['id' => 'SJ141E', 'option_letter' => 'e', 'option_text' => 'Ingatkan tentang konsekuensi yang bisa terjadi', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ142',
                        'version_id' => 'SJV01',
                        'number' => 42,
                        'question_text' => 'Bagaimana sikapmu terhadap informasi rahasia perusahaan?',
                        'competency' => 'PE',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ142A', 'option_letter' => 'a', 'option_text' => 'Jaga kerahasiaan dengan ketat sesuai aturan', 'score' => 4],
                        ['id' => 'SJ142B', 'option_letter' => 'b', 'option_text' => 'Share hanya dengan orang yang benar-benar perlu tahu', 'score' => 3],
                        ['id' => 'SJ142C', 'option_letter' => 'c', 'option_text' => 'Cerita ke keluarga atau teman dekat saja', 'score' => 1],
                        ['id' => 'SJ142D', 'option_letter' => 'd', 'option_text' => 'Tidak terlalu strict, asal tidak merugikan perusahaan', 'score' => 2],
                        ['id' => 'SJ142E', 'option_letter' => 'e', 'option_text' => 'Bebas share selama tidak ada larangan eksplisit', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ143',
                        'version_id' => 'SJV01',
                        'number' => 43,
                        'question_text' => 'Ketika diminta melakukan sesuatu yang kamu rasa tidak etis oleh atasan, bagaimana responmu?',
                        'competency' => 'PE',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ143A', 'option_letter' => 'a', 'option_text' => 'Ikuti saja karena itu perintah atasan', 'score' => 0],
                        ['id' => 'SJ143B', 'option_letter' => 'b', 'option_text' => 'Diskusikan concern etis secara profesional', 'score' => 4],
                        ['id' => 'SJ143C', 'option_letter' => 'c', 'option_text' => 'Tolak mentah-mentah tanpa penjelasan', 'score' => 1],
                        ['id' => 'SJ143D', 'option_letter' => 'd', 'option_text' => 'Cari cara untuk menghindari tanpa konflik', 'score' => 2],
                        ['id' => 'SJ143E', 'option_letter' => 'e', 'option_text' => 'Konsultasi dengan HR atau ethics committee', 'score' => 3],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ144',
                        'version_id' => 'SJV01',
                        'number' => 44,
                        'question_text' => 'Bagaimana cara kamu menangani conflict of interest dalam pekerjaan?',
                        'competency' => 'PE',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ144A', 'option_letter' => 'a', 'option_text' => 'Transparant disclosure ke pihak yang berkepentingan', 'score' => 4],
                        ['id' => 'SJ144B', 'option_letter' => 'b', 'option_text' => 'Handle secara hati-hati agar tidak ada yang dirugikan', 'score' => 2],
                        ['id' => 'SJ144C', 'option_letter' => 'c', 'option_text' => 'Biarkan saja selama tidak ada yang komplain', 'score' => 0],
                        ['id' => 'SJ144D', 'option_letter' => 'd', 'option_text' => 'Hindari situasi conflict of interest', 'score' => 3],
                        ['id' => 'SJ144E', 'option_letter' => 'e', 'option_text' => 'Manfaatkan untuk keuntungan pribadi', 'score' => 1],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ145',
                        'version_id' => 'SJV01',
                        'number' => 45,
                        'question_text' => 'Ketika ada kesalahan dalam laporan yang sudah diserahkan, apa yang kamu lakukan?',
                        'competency' => 'PE',
                        'page_number' => 4,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ145A', 'option_letter' => 'a', 'option_text' => 'Segera koreksi dan inform stakeholder terkait', 'score' => 4],
                        ['id' => 'SJ145B', 'option_letter' => 'b', 'option_text' => 'Tunggu ada yang menyadari baru perbaiki', 'score' => 1],
                        ['id' => 'SJ145C', 'option_letter' => 'c', 'option_text' => 'Biarkan saja kalau kesalahannya kecil', 'score' => 0],
                        ['id' => 'SJ145D', 'option_letter' => 'd', 'option_text' => 'Perbaiki di laporan selanjutnya tanpa mention kesalahan', 'score' => 2],
                        ['id' => 'SJ145E', 'option_letter' => 'e', 'option_text' => 'Diskusi dengan tim dulu sebelum mengambil tindakan', 'score' => 3],
                    ]
                ],

                // Questions 46-50: General Hardskills (GH)
                [
                    'question' => [
                        'id' => 'SJ146',
                        'version_id' => 'SJV01',
                        'number' => 46,
                        'question_text' => 'Ketika harus belajar tools atau software baru untuk pekerjaan, bagaimana pendekatanmu?',
                        'competency' => 'GH',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ146A', 'option_letter' => 'a', 'option_text' => 'Belajar hands-on sambil mengerjakan project real', 'score' => 4],
                        ['id' => 'SJ146B', 'option_letter' => 'b', 'option_text' => 'Ikut training formal atau course online dulu', 'score' => 3],
                        ['id' => 'SJ146C', 'option_letter' => 'c', 'option_text' => 'Minta bantuan rekan yang sudah mahir', 'score' => 2],
                        ['id' => 'SJ146D', 'option_letter' => 'd', 'option_text' => 'Baca dokumentasi dan tutorial secara mandiri', 'score' => 1],
                        ['id' => 'SJ146E', 'option_letter' => 'e', 'option_text' => 'Hindari dan gunakan cara lama yang sudah familiar', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ147',
                        'version_id' => 'SJV01',
                        'number' => 47,
                        'question_text' => 'Bagaimana cara kamu stay updated dengan perkembangan teknologi di bidangmu?',
                        'competency' => 'GH',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ147A', 'option_letter' => 'a', 'option_text' => 'Aktif mengikuti industry news, blog, dan forum professional', 'score' => 4],
                        ['id' => 'SJ147B', 'option_letter' => 'b', 'option_text' => 'Sesekali baca artikel kalau ada waktu luang', 'score' => 2],
                        ['id' => 'SJ147C', 'option_letter' => 'c', 'option_text' => 'Mengandalkan sharing dari rekan kerja', 'score' => 1],
                        ['id' => 'SJ147D', 'option_letter' => 'd', 'option_text' => 'Join komunitas dan networking events', 'score' => 3],
                        ['id' => 'SJ147E', 'option_letter' => 'e', 'option_text' => 'Tidak terlalu concern dengan update teknologi', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ148',
                        'version_id' => 'SJV01',
                        'number' => 48,
                        'question_text' => 'Ketika menggunakan data untuk mengambil keputusan, bagaimana cara kamu memastikan akurasinya?',
                        'competency' => 'GH',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ148A', 'option_letter' => 'a', 'option_text' => 'Validasi dari multiple sources dan cross-check', 'score' => 4],
                        ['id' => 'SJ148B', 'option_letter' => 'b', 'option_text' => 'Cek tanggal dan sumber data untuk memastikan relevansi', 'score' => 3],
                        ['id' => 'SJ148C', 'option_letter' => 'c', 'option_text' => 'Percaya saja kalau datanya dari sistem internal', 'score' => 1],
                        ['id' => 'SJ148D', 'option_letter' => 'd', 'option_text' => 'Spot check beberapa sample data untuk akurasi', 'score' => 2],
                        ['id' => 'SJ148E', 'option_letter' => 'e', 'option_text' => 'Tidak terlalu rigor dalam validasi data', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ149',
                        'version_id' => 'SJV01',
                        'number' => 49,
                        'question_text' => 'Bagaimana cara kamu mendokumentasikan proses kerja atau knowledge untuk tim?',
                        'competency' => 'GH',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ149A', 'option_letter' => 'a', 'option_text' => 'Buat dokumentasi detail dengan step-by-step dan screenshot', 'score' => 4],
                        ['id' => 'SJ149B', 'option_letter' => 'b', 'option_text' => 'Tulis point-point penting saja', 'score' => 2],
                        ['id' => 'SJ149C', 'option_letter' => 'c', 'option_text' => 'Cukup explain verbal ke tim', 'score' => 1],
                        ['id' => 'SJ149D', 'option_letter' => 'd', 'option_text' => 'Buat video tutorial untuk proses yang kompleks', 'score' => 3],
                        ['id' => 'SJ149E', 'option_letter' => 'e', 'option_text' => 'Tidak perlu dokumentasi, nanti juga tim bisa tanya langsung', 'score' => 0],
                    ]
                ],
                [
                    'question' => [
                        'id' => 'SJ150',
                        'version_id' => 'SJV01',
                        'number' => 50,
                        'question_text' => 'Ketika system atau tools yang biasa dipakai mengalami gangguan, apa yang kamu lakukan?',
                        'competency' => 'GH',
                        'page_number' => 5,
                        'is_active' => true,
                    ],
                    'options' => [
                        ['id' => 'SJ150A', 'option_letter' => 'a', 'option_text' => 'Cari alternative solution atau workaround', 'score' => 4],
                        ['id' => 'SJ150B', 'option_letter' => 'b', 'option_text' => 'Tunggu sampai system normal kembali', 'score' => 1],
                        ['id' => 'SJ150C', 'option_letter' => 'c', 'option_text' => 'Laporkan ke IT dan lakukan pekerjaan lain sambil menunggu', 'score' => 3],
                        ['id' => 'SJ150D', 'option_letter' => 'd', 'option_text' => 'Coba troubleshoot sendiri kalau memungkinkan', 'score' => 2],
                        ['id' => 'SJ150E', 'option_letter' => 'e', 'option_text' => 'Frustrated dan tidak bisa kerja produktif', 'score' => 0],
                    ]
                ],
            ];

            foreach ($questionsWithOptions as $data) {
                $question = SJTQuestion::updateOrCreate(
                    ['id' => $data['question']['id']],
                    $data['question']
                );

                foreach ($data['options'] as $optionData) {
                    $optionData['question_id'] = $question->id;
                    $optionData['competency_target'] = $data['question']['competency'];

                    SJTOption::updateOrCreate(
                        ['id' => $optionData['id']],
                        $optionData
                    );
                }
            }
        });
    }
}
