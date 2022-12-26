<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Program::truncate();
        $csvFile = fopen(base_path("docs/csv/program.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                Program::create(
                    [
                        'id_program' => \Str::uuid(),
                        'id_misi' => $data[0],
                        'nm_program' => $data[1],
                        'periode' => $data[2],
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
