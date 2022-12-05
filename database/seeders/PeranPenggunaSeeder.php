<?php

namespace Database\Seeders;

use App\Models\PeranPengguna;
use Illuminate\Database\Seeder;

class PeranPenggunaSeeder extends Seeder
{
    public function run()
    {
        PeranPengguna::truncate();
        PeranPengguna::insert([
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => 'e1333e2a-40bf-4ad7-94d5-22d72f6c4e69',
                'id_peran' => 1,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => '0c289cce-4442-4c93-8923-b9c816dd17ed',
                'id_peran' => 2,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => 'c640133b-a0ce-408f-a563-31405371ecf1',
                'id_peran' => 3,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => 'f0bc8307-0005-47d3-9e60-bdeca5c5dc07',
                'id_peran' => 4,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => 'bdfd7d92-7b58-4005-ac6a-d194c8e1f3a7',
                'id_peran' => 5,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => '9c2f46e8-b86b-474f-bfdf-5ad80c564107',
                'id_peran' => 6,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => 'f13d4957-eaf3-4a89-981a-f57364488867',
                'id_peran' => 7,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
            [
                'id_peran_pengguna' => guid(),
                'id_pengguna' => '39ed54c8-9457-4cf9-b9d9-2483ea644d03',
                'id_peran' => 99,
                'a_aktif' => 'Y',
                'created_at' => now(),
            ],
        ]);
    }
}
