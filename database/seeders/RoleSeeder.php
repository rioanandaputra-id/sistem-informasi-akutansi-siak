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
                'role_name' => 'Kepala PMI Wilayah',
                'created_at' => now(),
            ],
            [
                'id_role' => 2,
                'role_name' => 'Kepala UDD',
                'created_at' => now(),
            ],
            [
                'id_role' => 3,
                'role_name' => 'TIM RBA',
                'created_at' => now(),
            ],
            [
                'id_role' => 4,
                'role_name' => 'Kepala Bagian',
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
                'role_name' => 'Bendahara Kegiatan',
                'created_at' => now(),
            ],
        ]);
    }
}
