<?php

namespace Database\Seeders;

use App\Models\Misi;
use Illuminate\Database\Seeder;

class MisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Misi::truncate();
        $csvFile = fopen(base_path("docs/csv/misi.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Misi::create(
                    [
                        'id_misi' => $data[0],
                        'nm_misi' => $data[1],
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
