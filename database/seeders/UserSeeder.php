<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'id_user' => 'e1333e2a-40bf-4ad7-94d5-22d72f6c4e69',
                'id_role' => '1',
                'a_active' => '1',
                'email' => 'kepalapmikuasa@siak.com',
                'username' => 'kepalapmikuasa',
                'password' => bcrypt('kepalapmikuasa'),
                'full_name' => 'Kepala PMI Wilayah/Kuasa',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
            [
                'id_user' => '0c289cce-4442-4c93-8923-b9c816dd17ed',
                'id_role' => '2',
                'a_active' => '1',
                'email' => 'kepalauud@siak.com',
                'username' => 'kepalauud',
                'password' => bcrypt('kepalauud'),
                'full_name' => 'Kepla UDD',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
            [
                'id_user' => 'c640133b-a0ce-408f-a563-31405371ecf1',
                'id_role' => '3',
                'a_active' => '1',
                'email' => 'kordinatortimrba@siak.com',
                'username' => 'kordinatortimrba',
                'password' => bcrypt('kordinatortimrba'),
                'full_name' => 'Koordinator TIM RBA',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
            [
                'id_user' => 'f0bc8307-0005-47d3-9e60-bdeca5c5dc07',
                'id_role' => '4',
                'a_active' => '1',
                'email' => 'kepaladepartemenkabagian@siak.com',
                'username' => 'kepaladepartemenkabagian',
                'password' => bcrypt('kepaladepartemenkabagian'),
                'full_name' => 'Kepala Departemen/Ka Bagian',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
            [
                'id_user' => 'bdfd7d92-7b58-4005-ac6a-d194c8e1f3a7',
                'id_role' => '5',
                'a_active' => '1',
                'email' => 'bendaharapenerimaan@siak.com',
                'username' => 'bendaharapenerimaan',
                'password' => bcrypt('bendaharapenerimaan'),
                'full_name' => 'Bendahara Penerimaan',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
            [
                'id_user' => '9c2f46e8-b86b-474f-bfdf-5ad80c564107',
                'id_role' => '6',
                'a_active' => '1',
                'email' => 'bendaharapengeluaran@siak.com',
                'username' => 'bendaharapengeluaran',
                'password' => bcrypt('bendaharapengeluaran'),
                'full_name' => 'Bendahara Pengeluran',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
            [
                'id_user' => 'f13d4957-eaf3-4a89-981a-f57364488867',
                'id_role' => '7',
                'a_active' => '1',
                'email' => 'bendaharakegiatanpanitiapelaksana@siak.com',
                'username' => 'bendaharakegiatanpanitiapelaksana',
                'password' => bcrypt('bendaharakegiatanpanitiapelaksana'),
                'full_name' => 'Bendahara Kegiatan/Panitia Pelaksana',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
            [
                'id_user' => '39ed54c8-9457-4cf9-b9d9-2483ea644d03',
                'id_role' => '99',
                'a_active' => '1',
                'email' => 'developer@siak.com',
                'username' => 'developer',
                'password' => bcrypt('developer'),
                'full_name' => 'Developer',
                'gender' => 'L',
                'address' => '-',
                'phone' => '0',
                'created_at' => now(),
            ],
        ]);
    }
}
