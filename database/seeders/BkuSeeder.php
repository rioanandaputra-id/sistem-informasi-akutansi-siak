<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bku;
use App\Models\Spj;
use App\Models\DetailSpj;
use App\Models\Rba;
use App\Models\DetailRba;
use App\Models\KegiatanDivisi;
use App\Models\Dokumen;
use App\Models\LaksanaKegiatan;

class BkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bku::truncate();
        Spj::truncate();
        DetailSpj::truncate();
        Rba::truncate();
        DetailRba::truncate();
        KegiatanDivisi::truncate();
        Dokumen::truncate();
        LaksanaKegiatan::truncate();
    }
}
