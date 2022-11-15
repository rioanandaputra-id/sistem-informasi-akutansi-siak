<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run()
    {
        Divisi::insert([
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 1',
                'created_at' => now(),
            ],
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 2',
                'created_at' => now(),
            ],
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 3',
                'created_at' => now(),
            ],
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 4',
                'created_at' => now(),
            ],
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 5',
                'created_at' => now(),
            ],
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 6',
                'created_at' => now(),
            ],
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 7',
                'created_at' => now(),
            ],
            [
                'id_divisi' => guid(),
                'nm_divisi' => 'Divisi 8',
                'created_at' => now(),
            ],
        ]);
    }
}
