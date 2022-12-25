<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Akun;

class ImportPersediaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Akun::where('no_akun_induk', '5e297c0e-3f6a-4827-bb4b-0ef7c5398664')->delete();
        $csvFile = fopen(base_path("docs/csv/persediaan.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                Akun::create(
                    [
                        'id_akun' => \Str::uuid(),
                        'no_akun_induk' => '5e297c0e-3f6a-4827-bb4b-0ef7c5398664',
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
