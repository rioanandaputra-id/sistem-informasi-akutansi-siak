<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            [
                'id_role' => 1,
                'role_name' => 'Kepala PMI Wilayah/Kuasa',
                'created_at' => now(),
            ],
            [
                'id_role' => 2,
                'role_name' => 'Kepala UDD',
                'created_at' => now(),
            ],
            [
                'id_role' => 3,
                'role_name' => 'Koordinator TIM RBA',
                'created_at' => now(),
            ],
            [
                'id_role' => 4,
                'role_name' => 'Kepala Departemen/Ka Bagian',
                'created_at' => now(),
            ],
            [
                'id_role' => 5,
                'role_name' => 'Bendahara Penerimaan',
                'created_at' => now(),
            ],
            [
                'id_role' => 6,
                'role_name' => 'Bendahara Pengeluran',
                'created_at' => now(),
            ],
            [
                'id_role' => 7,
                'role_name' => 'Bendahara Kegiatan/Panitia Pelaksana',
                'created_at' => now(),
            ],
            [
                'id_role' => 99,
                'role_name' => 'Developer',
                'created_at' => now(),
            ],
        ]);
    }
}
