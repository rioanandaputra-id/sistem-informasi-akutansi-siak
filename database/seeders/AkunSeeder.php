<?php

namespace Database\Seeders;

use App\Models\Akun;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    public function run()
    {
        Akun::insert([
            [
                'id_akun' => guid(),
                'no_akun_induk' => '',
                'no_akun' => '5',
                'nm_akun' => 'Biaya',
                'keterangan' => '-',
                'sumber_akun' => '-',
                'created_at' => now(),
            ],
            [
                'id_akun' => guid(),
                'no_akun_induk' => '5',
                'no_akun' => '5.1',
                'nm_akun' => 'BIAYA BELANJA PEGAWAI',
                'keterangan' => '-',
                'sumber_akun' => '-',
                'created_at' => now(),
            ],
            [
                'id_akun' => guid(),
                'no_akun_induk' => '5',
                'no_akun' => '5.1.1',
                'nm_akun' => 'GAJI, HONOR, TUNJANGAN',
                'keterangan' => '-',
                'sumber_akun' => '-',
                'created_at' => now(),
            ],
        ]);
    }
}
