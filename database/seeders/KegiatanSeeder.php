<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kegiatan::truncate();
        $csvFile = fopen(base_path("docs/csv/kegiatan.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                Kegiatan::create(
                    [
                        'id_kegiatan' => $data[0],
                        'id_program' => $data[1],
                        'nm_kegiatan' => $data[2],
                        'a_aktif' => 1,
                        'created_at' => now()
                    ]
                );  
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
