<?php

namespace Database\Seeders;

use App\Models\Akun;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    public function run()
    {
        Akun::truncate();
        $csvFile = fopen(base_path("docs/csv/akun.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                Akun::create(
                    [
                        'id_akun' => $data[0],
                        'no_akun_induk' => $data[1],
                        'elemen' => $data[2],
                        'sub_elemen' => $data[3],
                        'jenis' => $data[4],
                        'no_akun' => $data[5],
                        'nm_akun' => $data[6],
                        'keterangan' => $data[7],
                        'created_at' => now()
                    ]
                );  
            }
            $firstline = false;
        }
        fclose($csvFile);

        // Akun::insert([
        //     [
        //         'id_akun' => guid(),
        //         'no_akun_induk' => '',
        //         'no_akun' => '5',
        //         'nm_akun' => 'Biaya',
        //         'keterangan' => '-',
        //         'sumber_akun' => '-',
        //         'created_at' => now(),
        //     ],
        //     [
        //         'id_akun' => guid(),
        //         'no_akun_induk' => '5',
        //         'no_akun' => '5.1',
        //         'nm_akun' => 'BIAYA BELANJA PEGAWAI',
        //         'keterangan' => '-',
        //         'sumber_akun' => '-',
        //         'created_at' => now(),
        //     ],
        //     [
        //         'id_akun' => guid(),
        //         'no_akun_induk' => '5',
        //         'no_akun' => '5.1.1',
        //         'nm_akun' => 'GAJI, HONOR, TUNJANGAN',
        //         'keterangan' => '-',
        //         'sumber_akun' => '-',
        //         'created_at' => now(),
        //     ],
        // ]);
    }
}
