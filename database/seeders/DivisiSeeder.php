<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run()
    {
        Divisi::truncate();
        Divisi::insert([
            [
                'id_divisi' => 'da138a9a-23ed-4941-932d-d1a457db0cdf',
                'nm_divisi' => '-',
                'created_at' => now(),
            ],
            [
                'id_divisi' => 'bd3da8a8-e86a-4cd8-8179-e331225cac0c',
                'nm_divisi' => 'Bagian Administrasi',
                'created_at' => now(),
            ],
            [
                'id_divisi' => 'ebc04110-3e03-4373-8e87-2e35f2fb660d',
                'nm_divisi' => 'Bagian Humas dan Pemasaran',
                'created_at' => now(),
            ],
            [
                'id_divisi' => '38401023-0263-43d9-a6d7-67b8a53227c2',
                'nm_divisi' => 'Bagian IT',
                'created_at' => now(),
            ],
            [
                'id_divisi' => 'f270590c-78f2-4980-be9a-edf0becc3f4f',
                'nm_divisi' => 'Bagian Keuangan',
                'created_at' => now(),
            ],
            [
                'id_divisi' => '8595d4f5-b63d-43b6-a799-37b9191a6708',
                'nm_divisi' => 'Bagian Pengawasan Mutu',
                'created_at' => now(),
            ],
            [
                'id_divisi' => '869f7e53-c445-44cf-8a41-f989c3156196',
                'nm_divisi' => 'Bagian Pengelolaan Darah',
                'created_at' => now(),
            ],
            [
                'id_divisi' => '6e484409-36db-4ce3-8223-0133eece5b7d',
                'nm_divisi' => 'Bagian Pengelolaan Darah dan Pengawasan Mutu',
                'created_at' => now(),
            ],
            [
                'id_divisi' => 'b28357d5-37f6-465c-a971-502bc850366e',
                'nm_divisi' => 'Bagian Pengelolaan Donor',
                'created_at' => now(),
            ],
        ]);
    }
}
