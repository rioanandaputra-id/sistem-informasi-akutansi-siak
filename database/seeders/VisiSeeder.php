<?php

namespace Database\Seeders;

use App\Models\Visi;
use Illuminate\Database\Seeder;

class VisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Visi::truncate();
        $csvFile = fopen(base_path("docs/csv/visi.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Visi::create(
                    [
                        'id_visi' => $data[0],
                        'nm_visi' => $data[1],
                        'periode' => $data[2],
                        'a_aktif' => $data[3],
                        'created_at' => now()
                    ]
                );
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
