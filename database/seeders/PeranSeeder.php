<?php

namespace Database\Seeders;

use App\Models\Peran;
use Illuminate\Database\Seeder;

class PeranSeeder extends Seeder
{
    public function run()
    {
        Peran::insert([
            [
                'id_peran' => 1,
                'nm_peran' => 'Kepala PMI Wilayah/Kuasa',
                'created_at' => now(),
            ],
            [
                'id_peran' => 2,
                'nm_peran' => 'Kepla UDD',
                'created_at' => now(),
            ],
            [
                'id_peran' => 3,
                'nm_peran' => 'Koordinator TIM RBA',
                'created_at' => now(),
            ],
            [
                'id_peran' => 4,
                'nm_peran' => 'Kepala Departemen/Ka Bagian',
                'created_at' => now(),
            ],
            [
                'id_peran' => 5,
                'nm_peran' => 'Bendahara Penerimaan',
                'created_at' => now(),
            ],
            [
                'id_peran' => 6,
                'nm_peran' => 'Bendahara Pengeluran',
                'created_at' => now(),
            ],
            [
                'id_peran' => 7,
                'nm_peran' => 'Bendahara Kegiatan/Panitia Pelaksana',
                'created_at' => now(),
            ],
            [
                'id_peran' => 99,
                'nm_peran' => 'Developer',
                'created_at' => now(),
            ],
        ]);
    }
}
