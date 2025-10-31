<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class St30QuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('st30_questions')->delete();

        DB::table('st30_questions')->insert(array (
            0 =>
            array (
                'id' => 'ST01',
                'version_id' => 'STV01',
                'number' => 1,
                'statement' => 'Pengelola urusan bisnis, organisasi, atau lembaga yang rapih dan baik',
                'typology_code' => 'AMB',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            1 =>
            array (
                'id' => 'ST02',
                'version_id' => 'STV01',
                'number' => 2,
                'statement' => 'Menjadi perwakilan dari suatu organisasi/institusi, baik resmi maupun tidak resmi',
                'typology_code' => 'ADM',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            2 =>
            array (
                'id' => 'ST03',
                'version_id' => 'STV01',
                'number' => 3,
                'statement' => 'Penggemar hal-hal detil, dan selalu melakukan analisa terhadap berbagai peristiwa',
                'typology_code' => 'ANA',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            3 =>
            array (
                'id' => 'ST04',
                'version_id' => 'STV01',
                'number' => 4,
                'statement' => 'Mampu dengan mudah mengorganisir berbagai hal, atau berbagai sumber daya yang dimilikinya',
                'typology_code' => 'ARR',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            4 =>
            array (
                'id' => 'ST05',
                'version_id' => 'STV01',
                'number' => 5,
                'statement' => 'Menyukai aktivitas memberikan perhatian atau mendampingi dan dukungan kepada orang lain',
                'typology_code' => 'CAR',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-10-20 08:15:19',
            ),
            5 =>
            array (
                'id' => 'ST06',
                'version_id' => 'STV01',
                'number' => 6,
                'statement' => 'Gemar memberikan perintah, kadang memaksa. Berani menghadapi masalah secara langsung',
                'typology_code' => 'CMD',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            6 =>
            array (
                'id' => 'ST07',
                'version_id' => 'STV01',
                'number' => 7,
                'statement' => 'Menyampaikan informasi, ide, perasaan dengan cara yang sederhana dan mudah dimengerti',
                'typology_code' => 'COM',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            7 =>
            array (
                'id' => 'ST08',
                'version_id' => 'STV01',
                'number' => 8,
                'statement' => 'Senang atau mampu menciptakan sesuatu yang baru, seperti penulis, ilmuwan, dll.',
                'typology_code' => 'CRE',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            8 =>
            array (
                'id' => 'ST09',
                'version_id' => 'STV01',
                'number' => 9,
                'statement' => 'Bisa membayangkan bagaimana sesuatu akan dibuat, dan bisa menggambar rancangan hal tersebut',
                'typology_code' => 'DES',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            9 =>
            array (
                'id' => 'ST10',
                'version_id' => 'STV01',
                'number' => 10,
                'statement' => 'Memberikan atau mengirimkan sesuatu kepada orang-orang tertentu, dan dalam jumlah tertentu',
                'typology_code' => 'DIS',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            10 =>
            array (
                'id' => 'ST11',
                'version_id' => 'STV01',
                'number' => 11,
                'statement' => 'Mendidik, atau berperan dalam merencanakan dan mengarahkan pendidikan',
                'typology_code' => 'EDU',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            11 =>
            array (
                'id' => 'ST12',
                'version_id' => 'STV01',
                'number' => 12,
                'statement' => 'Mampu melakukan studi dan analisis yang mendalam, dan membuat kesimpulan mengenai sesuatu',
                'typology_code' => 'EVA',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            12 =>
            array (
                'id' => 'ST13',
                'version_id' => 'STV01',
                'number' => 13,
                'statement' => 'Mampu menyelidiki sesuatu secara sistematis, sebagai upaya menemukan motif, untuk mengungkap kebenaran',
                'typology_code' => 'EXP',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            13 =>
            array (
                'id' => 'ST14',
                'version_id' => 'STV01',
                'number' => 14,
                'statement' => 'Mampu menginterpretasikan sesuatu, termasuk menerjemahkannya ke dalam bahasa lain',
                'typology_code' => 'INT',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            14 =>
            array (
                'id' => 'ST15',
                'version_id' => 'STV01',
                'number' => 15,
                'statement' => 'Mampu membuat jurnal, buku harian atau catatan kejadian sehari-hari, atau menulis laporan dan berita untuk disiarkan',
                'typology_code' => 'COM',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            15 =>
            array (
                'id' => 'ST16',
                'version_id' => 'STV01',
                'number' => 16,
                'statement' => 'Mampu merumuskan strategi promosi, untuk mendorong orang agar mereka membeli lebih banyak produk/jasanya',
                'typology_code' => 'AMB',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            16 =>
            array (
                'id' => 'ST17',
                'version_id' => 'STV01',
                'number' => 17,
                'statement' => 'Mampu menjadi penengah, mengatasi dan menyelesaikan konflik antar dua pihak yang bertikai',
                'typology_code' => 'CMD',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            17 =>
            array (
                'id' => 'ST18',
                'version_id' => 'STV01',
                'number' => 18,
                'statement' => 'Membuat orang lain menjadi bersemangat, tertarik, dan berkomitmen untuk melakukan sesuatu dengan sebaik mungkin',
                'typology_code' => 'CMD',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            18 =>
            array (
                'id' => 'ST19',
                'version_id' => 'STV01',
                'number' => 19,
                'statement' => 'Mengoperasikan dan menjaga mesin-mesin, instrument, atau peralatan lain',
                'typology_code' => 'ARR',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            19 =>
            array (
                'id' => 'ST20',
                'version_id' => 'STV01',
                'number' => 20,
                'statement' => 'Menghasilkan suatu produk, terutama produk yang dihasilkan oleh proses industri atau manufaktur',
                'typology_code' => 'CRE',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            20 =>
            array (
                'id' => 'ST21',
                'version_id' => 'STV01',
                'number' => 21,
                'statement' => 'Memastikan kualitas yang memadai dan baik, terutama dalam produk yang dibuat',
                'typology_code' => 'EVA',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            21 =>
            array (
                'id' => 'ST22',
                'version_id' => 'STV01',
                'number' => 22,
                'statement' => 'Mengembalikan sesuatu menjadi seperti kondisi/keadaan semula, atau normal, atau menjadi lebih baik',
                'typology_code' => 'CAR',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            22 =>
            array (
                'id' => 'ST23',
                'version_id' => 'STV01',
                'number' => 23,
                'statement' => 'Melindungi keselamatan atau keamanan sesuatu atau seseorang dari bahaya kerusakan, kehilangan dan pencurian',
                'typology_code' => 'CAR',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            23 =>
            array (
                'id' => 'ST24',
                'version_id' => 'STV01',
                'number' => 24,
                'statement' => 'Hebat dalam meyakinkan dan mempengaruhi orang lain untuk membeli barang/jasa yang ditawarkannya',
                'typology_code' => 'AMB',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            24 =>
            array (
                'id' => 'ST25',
                'version_id' => 'STV01',
                'number' => 25,
                'statement' => 'Senang melayani dan mendahulukan orang lain',
                'typology_code' => 'CAR',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            25 =>
            array (
                'id' => 'ST26',
                'version_id' => 'STV01',
                'number' => 26,
                'statement' => 'Mampu dan pandai memilih seseorang untuk ditempatkan pada suatu posisi atau tugas tertentu',
                'typology_code' => 'ARR',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            26 =>
            array (
                'id' => 'ST27',
                'version_id' => 'STV01',
                'number' => 27,
                'statement' => 'Mampu melakukan perencanaan jangka panjang',
                'typology_code' => 'ADM',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            27 =>
            array (
                'id' => 'ST28',
                'version_id' => 'STV01',
                'number' => 28,
                'statement' => 'Gemar mengkombinasikan berbagai pandangan, ide, obyek, dll. menjadi sesuatu hal yang baru',
                'typology_code' => 'CRE',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            28 =>
            array (
                'id' => 'ST29',
                'version_id' => 'STV01',
                'number' => 29,
                'statement' => 'Mampu dan pandai melakukan tugas pengelolaan keuangan, pembukuan dan akuntansi',
                'typology_code' => 'ANA',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
            29 =>
            array (
                'id' => 'ST30',
                'version_id' => 'STV01',
                'number' => 30,
                'statement' => 'Gemar berpikir jauh ke depan melampaui cakrawala',
                'typology_code' => 'EXP',
                'is_active' => 1,
                'created_at' => '2025-08-20 06:20:50',
                'updated_at' => '2025-08-20 06:20:50',
            ),
        ));


    }
}
