<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run()
    {
        RoleUser::truncate();
        RoleUser::insert([
            [
                'id_role_user' => '1c97b173-c956-4224-9150-6504086920ff',
                'id_role' => '1',
                'id_user' => 'e1333e2a-40bf-4ad7-94d5-22d72f6c4e69',
                'a_active' => 1,
                'created_at' => now(),
            ],
            [
                'id_role_user' => '61120e1d-7e6c-445f-8464-52429590fe06',
                'id_role' => '2',
                'id_user' => '0c289cce-4442-4c93-8923-b9c816dd17ed',
                'a_active' => 1,
                'created_at' => now(),
            ],
            [
                'id_role_user' => '17d77479-d589-4afe-a924-895fabf5929f',
                'id_role' => '3',
                'id_user' => 'c640133b-a0ce-408f-a563-31405371ecf1',
                'a_active' => 1,
                'created_at' => now(),
            ],
            // ==================BAGIAN KEUANGAN====================
            [
                'id_role_user' => '8d311306-dd2d-4bbd-8a65-b422fc42809c',
                'id_role' => '4',
                'id_user' => 'f0bc8307-0005-47d3-9e60-bdeca5c5dc07',
                'a_active' => 1,
                'created_at' => now(),
            ],
            [
                'id_role_user' => '9adfba9d-15d1-4560-a0d5-ea43e0c0ba45',
                'id_role' => '7',
                'id_user' => 'f13d4957-eaf3-4a89-981a-f57364488867',
                'a_active' => 1,
                'created_at' => now(),
            ],
            [
                'id_role_user' => '20a3de57-73a6-42af-b238-903f70092f30',
                'id_role' => '5',
                'id_user' => 'bdfd7d92-7b58-4005-ac6a-d194c8e1f3a7',
                'a_active' => 1,
                'created_at' => now(),
            ],
            [
                'id_role_user' => '6ee988bb-aafd-496e-b288-de804c9e4d51',
                'id_role' => '6',
                'id_user' => '9c2f46e8-b86b-474f-bfdf-5ad80c564107',
                'a_active' => 1,
                'created_at' => now(),
            ],
            // ==================BAGIAN IT====================
            [
                'id_role_user' => 'db53f336-e902-4b14-baf6-24a0ce774958',
                'id_role' => '4',
                'id_user' => 'a68867b1-2345-4775-93c1-15ff5d9120f5',
                'a_active' => 1,
                'created_at' => now(),
            ],
            [
                'id_role_user' => 'd763c72d-4eeb-4ccd-9d41-372b9ba33525',
                'id_role' => '7',
                'id_user' => 'e94dd496-6864-4424-9abd-ab0218106844',
                'a_active' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
