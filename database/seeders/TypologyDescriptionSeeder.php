<?php

namespace Database\Seeders;

use App\Models\TypologyDescription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypologyDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typologies = [
            [
                'typology_code' => 'AMB',
                'typology_name' => 'Ambassador',
                'strength_description' => 'Bersahabat, menyampaikan dan menjelaskan sesuatu, senang melayani dan bertanggung jawab',
                'weakness_description' => 'Terlalu mengutamakan hubungan, kurang tegas dalam pengambilan keputusan'
            ],
            [
                'typology_code' => 'ADM',
                'typology_name' => 'Administrator',
                'strength_description' => 'Memiliki pola kerja yang terstruktur, terencana, rapih, suka melayani serta menjunjung tinggi tanggung jawab dan ketaatan tata tertib',
                'weakness_description' => 'Terlalu kaku pada aturan, sulit beradaptasi dengan perubahan mendadak'
            ],
            [
                'typology_code' => 'ANA',
                'typology_name' => 'Analyst',
                'strength_description' => 'Memiliki ketertarikan dengan hitung-menghitung berhubungan dengan angka, data dan analisis',
                'weakness_description' => 'Terlalu fokus pada detail, kadang kehilangan gambaran besar'
            ],
            [
                'typology_code' => 'ARR',
                'typology_name' => 'Arranger',
                'strength_description' => 'Suka mengatur seorang atau sekelompok untuk bekerjasama dalam hal penempatan atau penugasan orang, barang ataupun event',
                'weakness_description' => 'Cenderung terlalu mengontrol, sulit mendelegasikan tugas'
            ],
            [
                'typology_code' => 'CAR',
                'typology_name' => 'Caretaker',
                'strength_description' => 'Memberikan perhatian atau merawat orang lain yang memiliki masalah fisik, mental, medis atau kesejahteraan umum. mampu merasakan perasaan orang lain serta terdorong membantu orang lain',
                'weakness_description' => 'Terlalu peduli pada orang lain, mengabaikan kebutuhan diri sendiri'
            ],
            [
                'typology_code' => 'CMD',
                'typology_name' => 'Commander',
                'strength_description' => 'Memiliki kemampuan mengatur dan mengawasi dalam melaksanakan tugas, tegas, mungkin keras kepala, berani mengambil tanggung jawab',
                'weakness_description' => 'Terlalu dominan, sulit menerima masukan dari orang lain'
            ],
            [
                'typology_code' => 'COM',
                'typology_name' => 'Communicator',
                'strength_description' => 'Mudah dalam mengkomunikasikan sesuatu secara sederhana, menarik dan mudah dimengerti',
                'weakness_description' => 'Kadang terlalu banyak bicara, kurang mendengarkan orang lain'
            ],
            [
                'typology_code' => 'CRE',
                'typology_name' => 'Creator',
                'strength_description' => 'Memiliki daya cipta, kreatif, inovatif, suka mencoba hal baru dan tidak suka rutinitas',
                'weakness_description' => 'Sulit fokus pada satu ide, mudah terdistraksi, kurang terstruktur'
            ],
            [
                'typology_code' => 'DES',
                'typology_name' => 'Designer',
                'strength_description' => 'Memiliki jiwa seni, peka terhadap keindahan, harmoni warna dan bentuk',
                'weakness_description' => 'Cenderung terjebak pada detail visual, sulit memilih ide utama'
            ],
            [
                'typology_code' => 'DIS',
                'typology_name' => 'Distributor',
                'strength_description' => 'Senang memberikan/menyebarkan/menyalurkan barang, informasi atau keahlian kepada orang lain',
                'weakness_description' => 'Cenderung bekerja berulang tanpa refleksi, kurang fleksibel'
            ],
            [
                'typology_code' => 'EDU',
                'typology_name' => 'Educator',
                'strength_description' => 'Senang mengajar, membimbing, melatih atau mengembangkan orang lain',
                'weakness_description' => 'Cenderung terlalu mengontrol, sulit menerima metode belajar berbeda'
            ],
            [
                'typology_code' => 'EVA',
                'typology_name' => 'Evaluator',
                'strength_description' => 'Senang menilai, mengevaluasi, membandingkan atau mengkritisi sesuatu berdasarkan standar tertentu',
                'weakness_description' => 'Terlalu lama dalam mengambil keputusan, cenderung perfeksionis'
            ],
            [
                'typology_code' => 'EXP',
                'typology_name' => 'Explorer',
                'strength_description' => 'Senang menjelajahi, mencari, meneliti, menemukan hal baru atau mengumpulkan sesuatu',
                'weakness_description' => 'Terlalu banyak menganalisis, sulit mengambil keputusan cepat'
            ],
            [
                'typology_code' => 'INT',
                'typology_name' => 'Interpreter',
                'strength_description' => 'Mampu menerjemahkan, menjelaskan atau menafsirkan sesuatu kepada orang lain',
                'weakness_description' => 'Cenderung terlalu banyak bicara, sulit menyampaikan secara singkat'
            ]
        ];

        foreach ($typologies as $typology) {
            TypologyDescription::updateOrCreate(
                ['typology_code' => $typology['typology_code']],
                $typology
            );
        }
    }
} 
