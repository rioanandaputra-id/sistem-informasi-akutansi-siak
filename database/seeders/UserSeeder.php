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
                'id_divisi' => 'da138a9a-23ed-4941-932d-d1a457db0cdf',
                'email' => 'kepalawilayah@siak.com',
                'username' => 'kepalawilayah',
                'password' => bcrypt('kepalawilayah'),
                'full_name' => 'Kepala PMI Wilayah',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            [
                'id_user' => '0c289cce-4442-4c93-8923-b9c816dd17ed',
                'id_divisi' => 'da138a9a-23ed-4941-932d-d1a457db0cdf',
                'email' => 'kepalauud@siak.com',
                'username' => 'kepalauud',
                'password' => bcrypt('kepalauud'),
                'full_name' => 'Kepala UDD',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            [
                'id_user' => 'c640133b-a0ce-408f-a563-31405371ecf1',
                'id_divisi' => 'da138a9a-23ed-4941-932d-d1a457db0cdf',
                'email' => 'timrba@siak.com',
                'username' => 'timrba',
                'password' => bcrypt('timrba'),
                'full_name' => 'TIM RBA',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            // ==================BAGIAN KEUANGAN====================
            [
                'id_user' => 'f0bc8307-0005-47d3-9e60-bdeca5c5dc07',
                'id_divisi' => 'f270590c-78f2-4980-be9a-edf0becc3f4f',
                'email' => 'kepalabagiankeauangan@siak.com',
                'username' => 'kepalabagiankeauangan',
                'password' => bcrypt('kepalabagiankeauangan'),
                'full_name' => 'Kepala Bagian',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            [
                'id_user' => 'f13d4957-eaf3-4a89-981a-f57364488867',
                'id_divisi' => 'f270590c-78f2-4980-be9a-edf0becc3f4f',
                'email' => 'bendkegiatankeuangan@siak.com',
                'username' => 'bendkegiatankeuangan',
                'password' => bcrypt('bendkegiatankeuangan'),
                'full_name' => 'Bendahara Kegiatan',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            [
                'id_user' => 'bdfd7d92-7b58-4005-ac6a-d194c8e1f3a7',
                'id_divisi' => 'f270590c-78f2-4980-be9a-edf0becc3f4f',
                'email' => 'bendpenerimaankeuangan@siak.com',
                'username' => 'bendpenerimaankeuangan',
                'password' => bcrypt('bendpenerimaankeuangan'),
                'full_name' => 'Bendahara Penerimaan',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            [
                'id_user' => '9c2f46e8-b86b-474f-bfdf-5ad80c564107',
                'id_divisi' => 'f270590c-78f2-4980-be9a-edf0becc3f4f',
                'email' => 'bendpengeluarankeuangan@siak.com',
                'username' => 'bendpengeluarankeuangan',
                'password' => bcrypt('bendpengeluaran'),
                'full_name' => 'Bendahara Pengeluran',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            // ==================BAGIAN IT====================
            [
                'id_user' => 'a68867b1-2345-4775-93c1-15ff5d9120f5',
                'id_divisi' => '38401023-0263-43d9-a6d7-67b8a53227c2',
                'email' => 'kepalabagianit@siak.com',
                'username' => 'kepalabagianit',
                'password' => bcrypt('kepalabagianit'),
                'full_name' => 'Kepala Bagian',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
            [
                'id_user' => 'e94dd496-6864-4424-9abd-ab0218106844',
                'id_divisi' => '38401023-0263-43d9-a6d7-67b8a53227c2',
                'email' => 'bendkegiatanit@siak.com',
                'username' => 'bendkegiatanit',
                'password' => bcrypt('bendkegiatanit'),
                'full_name' => 'Bendahara Kegiatan',
                'nik' => rand(1000000000000000, 9999999999999999),
                'gender' => 'L',
                'address' => '-',
                'phone' => rand(100000000000, 999999999999),
                'created_at' => now(),
            ],
        ]);
    }
}
