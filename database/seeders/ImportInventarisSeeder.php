<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Akun;

class ImportInventarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Akun::where('no_akun_induk', '313dbf38-3feb-452b-abd1-13084e90bb05')->delete();
        $csvFile = fopen(base_path("docs/csv/inventaris.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                Akun::create(
                    [
                        'id_akun' => \Str::uuid(),
                        'no_akun_induk' => '313dbf38-3feb-452b-abd1-13084e90bb05',
                        'elemen' => $data[0],
                        'sub_elemen' => $data[1],
                        'jenis' => $data[2],
                        'no_akun' => $data[3],
                        'nm_akun' => $data[4],
                        'keterangan' => $data[5],
                        'created_at' => now()
                    ]
                );  
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}