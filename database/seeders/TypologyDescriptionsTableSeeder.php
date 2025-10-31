<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypologyDescriptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('typology_descriptions')->delete();

        DB::table('typology_descriptions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'typology_code' => 'AMB',
                'typology_name' => 'Ambassador',
                'strength_description' => 'Bersahabat, menyampaikan dan menjelaskan sesuatu, senang melayani dan bertanggung jawab',
                'weakness_description' => 'Sulit berkata tidak, terlalu fokus pada orang lain, menghindari konflik, mudah lelah karena tanggung jawab berlebih, cenderung perfeksionis dalam membantu orang lain',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            1 =>
            array (
                'id' => 2,
                'typology_code' => 'ADM',
                'typology_name' => 'Administrator',
                'strength_description' => 'Memiliki pola kerja yang terstruktur, terencana, rapih, suka melayani serta menjunjung tinggi tanggung jawab dan ketaatan tata tertib',
                'weakness_description' => 'Kaku terhadap perubahan, kurang fleksibel, mudah stres saat rencana terganggu, sulit mendelegasikan tugas, terlalu menuntut kesempurnaan dari diri sendiri dan orang lain',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            2 =>
            array (
                'id' => 3,
                'typology_code' => 'ANA',
                'typology_name' => 'Analyst',
                'strength_description' => 'Memiliki ketertarikan dengan hitung-menghitung berhubungan dengan angka, data dan analisis',
                'weakness_description' => 'Terlalu fokus pada detail, kurang peka terhadap aspek emosional, cenderung kaku dalam berpikir, sulit melihat gambaran besar, mudah terjebak dalam perhitungan yang rumit',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            3 =>
            array (
                'id' => 4,
                'typology_code' => 'ARR',
                'typology_name' => 'Arranger',
                'strength_description' => 'Suka mengatur seorang atau sekelompok untuk bekerjasama dalam hal penempatan atau penugasan orang, barang ataupun event',
                'weakness_description' => 'Cenderung dominan, sulit menerima masukan, terlalu mengontrol, kurang memberi ruang inisiatif orang lain, mudah frustasi jika tim tidak berjalan sesuai rencana',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            4 =>
            array (
                'id' => 5,
                'typology_code' => 'CAR',
                'typology_name' => 'Caretaker',
                'strength_description' => 'Memberikan perhatian atau merawat orang lain yang memiliki masalah fisik, mental, medis atau kesejahteraan umum. mampu merasakan perasaan orang lain serta terdorong membantu orang lain',
                'weakness_description' => 'Mudah terbawa perasaan, sulit menjaga batasan pribadi, cepat lelah secara emosional, cenderung mengabaikan kebutuhan diri sendiri, rentan merasa bersalah jika tidak bisa membantu',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            5 =>
            array (
                'id' => 6,
                'typology_code' => 'CMD',
                'typology_name' => 'Commander',
                'strength_description' => 'Memiliki kemampuan mengantur dan mengawasi dalam melaksanakan tugas, tegas, mungkin keras kepala, berani mengambil tanggung jawab',
                'weakness_description' => 'Cenderung otoriter, kurang mendengarkan pendapat orang lain, sulit berkompromi, terlalu fokus pada kontrol, bisa terlihat kaku atau kurang empati dalam kepemimpinan',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            6 =>
            array (
                'id' => 7,
                'typology_code' => 'COM',
                'typology_name' => 'Communicator',
                'strength_description' => 'Mudah dalam mengkomunikasikan sesuatu secara sederhana, menarik dan mudah dimengerti',
                'weakness_description' => 'Cenderung menyederhanakan hal yang kompleks, kurang mendalam dalam penyampaian, terlalu fokus pada gaya daripada isi, mudah bosan dengan topik yang terlalu teknis atau detail',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            7 =>
            array (
                'id' => 8,
                'typology_code' => 'CRE',
                'typology_name' => 'Creator',
                'strength_description' => 'Memiliki imajinasi dalam suatu rancangan, memiliki ide yang muncul secara spontan dan bervariasi',
                'weakness_description' => 'Sulit fokus pada satu ide, mudah terdistraksi, kurang terstruktur, sulit merealisasikan ide, cenderung mengabaikan detail teknis atau praktis',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            8 =>
            array (
                'id' => 9,
                'typology_code' => 'DES',
                'typology_name' => 'Designer',
                'strength_description' => 'Senang membuat gambar atau illustrasi bagunan atau produk yang akan dibuat, memiliki sifat analitis juga memiliki beragam ide kreatif',
                'weakness_description' => 'Cenderung terjebak pada detail visual, sulit memilih ide utama, perfeksionis, lambat ambil keputusan, dan kurang kolaboratif',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            9 =>
            array (
                'id' => 10,
                'typology_code' => 'DIS',
                'typology_name' => 'Distributor',
                'strength_description' => 'Memiliki sifat ulet, teguh dalam beraktivitas pekerjaan mencakup mengirimkan sesuatu berupa barang, surat atau artikel pada saat yang hampir bersamaan',
                'weakness_description' => 'Cenderung bekerja berulang tanpa refleksi, mudah lelah secara fisik, kurang fleksibel saat situasi berubah, dan minim inovasi dalam metode',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            10 =>
            array (
                'id' => 11,
                'typology_code' => 'EDU',
                'typology_name' => 'Educator',
                'strength_description' => 'Mengajar, membimbing, menyampaikan, melatih ilmua dan/atau keterampilan agar bisa dipahami orang lain, selalu ingin memajukan orang lain dan senang melihat kemajuan orang yang dibimbingnya',
                'weakness_description' => 'Cenderung terlalu mengontrol, sulit menerima metode belajar berbeda, mudah frustrasi saat perkembangan lambat, dan mengabaikan kebutuhan dirinya sendiri',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            11 =>
            array (
                'id' => 12,
                'typology_code' => 'EVA',
                'typology_name' => 'Evaluator',
                'strength_description' => 'Mengumpulkan informasi, mempelajari dan menimbang dalam rangka memutuskan sesuatu terkait nilai. mutu, kepentingan atau kondisi',
                'weakness_description' => 'Terlalu lama dalam mengambil keputusan, cenderung perfeksionis, sulit menerima ketidakpastian, dan bisa mengabaikan dinamika atau konteks sosial',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            12 =>
            array (
                'id' => 13,
                'typology_code' => 'EXP',
                'typology_name' => 'Explorer',
                'strength_description' => 'Senang mempelajari latar belakang, senang berolah pikir dan melakukan penelitian untuk menemukan fakta-fakta',
                'weakness_description' => 'Terlalu banyak menganalisis, sulit mengambil keputusan cepat, tenggelam dalam detail, cenderung skeptis, kurang responsif terhadap hal yang bersifat praktis atau instan',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            13 =>
            array (
                'id' => 14,
                'typology_code' => 'INT',
                'typology_name' => 'Interpreter',
                'strength_description' => 'Suka menjelaskan arti atau makna dari sesuatu sehingga mudah dipahami orang lain, senang berkomunikasi baik dalam bentuk tulisan dan lisan',
                'weakness_description' => 'Cenderung terlalu banyak bicara, sulit menyampaikan secara singkat, mudah terjebak dalam penjelasan yang berulang, kurang mendengarkan, terlalu fokus pada sudut pandang sendiri',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            14 =>
            array (
                'id' => 15,
                'typology_code' => 'JOU',
                'typology_name' => 'Journalist',
                'strength_description' => 'Senang mengkomunikasikan idenya, suka mengumpulkan berbagai informasi dengan rapih, terstruktur dan teratur',
                'weakness_description' => 'Terlalu terpaku pada struktur, sulit menerima perubahan mendadak, cenderung overthinking saat ide tidak tersampaikan dengan baik, kurang fleksibel dalam menyusun informasi, bisa terjebak dalam perencanaan tanpa eksekusi',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            15 =>
            array (
                'id' => 16,
                'typology_code' => 'MAR',
                'typology_name' => 'Marketer',
                'strength_description' => 'Senang berpikiran strategis, menyampaikan atau mengkomuikasikan sesuatu, memiliki ide kreatif dan senang menonjolkan kelebihan produk/jasa yang diusungnya',
                'weakness_description' => 'Cenderung terlalu fokus pada gambaran besar, kurang memperhatikan detail teknis, bisa terlalu percaya diri, berisiko memaksakan ide, dan mudah kecewa jika ide tidak diterima dengan antusias',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            16 =>
            array (
                'id' => 17,
                'typology_code' => 'MED',
                'typology_name' => 'Mediator',
                'strength_description' => 'Mampu mengatasi dan menyelesaikan konflik antara dua belah pihak yang berselisih, tegas menghadapi orang dan tidak menyukai konflik',
                'weakness_description' => 'Cenderung menghindari konfrontasi langsung, bisa terlihat terlalu mengontrol, sulit bersikap netral sepenuhnya, mudah terbebani secara emosional, dan kurang fleksibel dalam menghadapi dinamika emosi orang lain',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            17 =>
            array (
                'id' => 18,
                'typology_code' => 'MOT',
                'typology_name' => 'Motivator',
                'strength_description' => 'Senang memberikan semangat kepada individu atau sekelompok agar bisa menjadi lebih baik, melalui gaya dan stylenya sendiri',
                'weakness_description' => 'Fokus pada gaya bukan isi, tampak berlebihan, mudah kecewa tanpa respons positif, kurang peka pada kebutuhan gaya berbeda',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            18 =>
            array (
                'id' => 19,
                'typology_code' => 'OPE',
                'typology_name' => 'Operator',
                'strength_description' => 'Senang menjalankan, mengoperasikan dan merawat mesin, peralatan, proses atau sistem, senang melayani, teratur, disiplin, serta gigih bekerja',
                'weakness_description' => 'Kurang fleksibel terhadap perubahan, cenderung monoton, sulit beradaptasi dengan hal baru, terlalu fokus pada prosedur, dan kurang tertarik pada aspek kreatif atau inovatif',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            19 =>
            array (
                'id' => 20,
                'typology_code' => 'PRO',
                'typology_name' => 'Producer',
                'strength_description' => 'Senang memasang, memproduksi, membangun mesin, perangkat atau bangunan, sosok pekerja keras, teratur dan gerak cepat',
                'weakness_description' => 'Kurang sabar saat lambat, abaikan rencana jangka panjang, terburu ambil keputusan, cuek emosi, fokus hasil, lupakan proses',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            20 =>
            array (
                'id' => 21,
                'typology_code' => 'QCA',
                'typology_name' => 'Quality Controller',
            'strength_description' => 'Suka mengawasi dan memeriksa suatu proses pembuatan produk/jasa sesuai dengan ketentuan kualitas/estetika (SOP)/ sosok perfeksionis, teliti dan fokus',
                'weakness_description' => 'Terlalu perfeksionis, sulit puas dengan hasil, cenderung micromanage, mudah stres jika standar tidak terpenuhi, dan kurang fleksibel terhadap improvisasi atau perubahan mendadak',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            21 =>
            array (
                'id' => 22,
                'typology_code' => 'RES',
                'typology_name' => 'Restorer',
                'strength_description' => 'Menyukai memperbaiki atau memulihkan sesuatu ke fungsi normalnya atau lebih baik, senang mengutak-atik, mencari tahu sistem kerja dan mengembalikan fungsi sesuatu',
                'weakness_description' => 'Terpaku cara lama, sulit menerima kegagalan, mudah frustrasi tanpa solusi, terlalu teknis, abaikan aspek emosional dan sosial',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            22 =>
            array (
                'id' => 23,
                'typology_code' => 'SAF',
                'typology_name' => 'Safekeeper',
                'strength_description' => 'Memiliki sifat teliti, hati-hati, waspada dan memegang teguh tanggung jawab',
                'weakness_description' => 'Lambat ambil keputusan, terlalu khawatir salah, sulit ambil risiko, kurang spontan, overthinking saat menghadapi tugas',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            23 =>
            array (
                'id' => 24,
                'typology_code' => 'SEL',
                'typology_name' => 'Seller',
                'strength_description' => 'Umumnya menyukai berhubungan dengan orang lain, baik untuk mempengaruhi, bekerjasama atau melayani',
                'weakness_description' => 'Mudah terpengaruh oleh opini orang lain, sulit bekerja sendiri, cenderung menghindari konflik demi menjaga hubungan, terlalu fokus menyenangkan orang lain, dan rentan kelelahan sosial',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            24 =>
            array (
                'id' => 25,
                'typology_code' => 'SER',
                'typology_name' => 'Server',
                'strength_description' => 'Memiliki sifat yang tulus dalam bekerja dan melayani orang lain, suka melayani orang lain dan mendahulukan kepantingan orang lain',
                'weakness_description' => 'Cenderung mengabaikan kebutuhan diri sendiri, mudah dimanfaatkan, sulit mengatakan tidak, cepat merasa bersalah, dan rentan kelelahan emosional karena terlalu banyak memberi',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            25 =>
            array (
                'id' => 26,
                'typology_code' => 'SLC',
                'typology_name' => 'Selector',
                'strength_description' => 'Memiliki kemampuan untuk memilih dan merekrut seseorang sesuai dengan apa yang dibutuhkan, memiliki insting kuat dalam melihat keunikan sifat orang lain sehingga dapat memperkirakan potensinya',
                'weakness_description' => 'Menilai pakai intuisi tanpa data, bias kesan pertama, terlalu percaya diri, sulit objektif, rawan salah menempatkan orang',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            26 =>
            array (
                'id' => 27,
                'typology_code' => 'STR',
                'typology_name' => 'Strategist',
                'strength_description' => 'Memiliki kemampuan perencanaan yang baik untuk mencapai goals, memiliki insting kuat dalam memilih solusi yang tepat, bijaksana dan penuh pertimbangan',
                'weakness_description' => 'Cenderung overthinking, terlalu lama dalam mengambil keputusan, sulit bersikap spontan, terlalu banyak mempertimbangkan risiko, dan bisa kurang fleksibel dalam situasi mendesak',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            27 =>
            array (
                'id' => 28,
                'typology_code' => 'SYN',
                'typology_name' => 'Synthesizer',
                'strength_description' => 'Mengkombinasikan berbagai elemen, ide dan informasi menjadi sesuatu yang baru seperti menggabungkan beberapa ide, teori atau temuan menjadi suatu temuan baru',
                'weakness_description' => 'Berpikir terlalu kompleks, sulit menyederhanakan ide, kehilangan fokus tujuan, terlalu banyak eksperimen, ide terus berkembang, sulit diselesaikan',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            28 =>
            array (
                'id' => 29,
                'typology_code' => 'TRE',
                'typology_name' => 'Treasurer',
                'strength_description' => 'Memiliki kemampuan analitis, rapi, teratur, teliti dan bertanggung jawab dalam menata keuangan dengan catatan yang rapih, tertata dan tanpa kesalahan',
                'weakness_description' => 'Kaku dalam pengelolaan, sulit berimprovisasi, mudah stres saat ada kesalahan, perfeksionis, terlalu fokus detail, abaikan gambaran besar',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
            29 =>
            array (
                'id' => 30,
                'typology_code' => 'VIS',
                'typology_name' => 'Visionary',
                'strength_description' => 'Senang memimpikan apa yang mungkin terjadi jauh ke masa depan sehingga dapat menentukan tujuan jangka panjang yang benar',
                'weakness_description' => 'Kurang fokus pada langkah konkret, mudah berangan-angan, sulit menyesuaikan realita, abaikan detail teknis, sulit adaptasi perubahan',
                'created_at' => '2025-08-20 06:19:14',
                'updated_at' => '2025-08-20 06:19:14',
            ),
        ));


    }
}
