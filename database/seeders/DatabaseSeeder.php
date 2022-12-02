<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(DivisiSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RoleUserSeeder::class);
        $this->call(AkunSeeder::class);
        $this->call(VisiSeeder::class);
        $this->call(MisiSeeder::class);
        $this->call(ProgramSeeder::class);
        $this->call(KegiatanSeeder::class);
    }
}
