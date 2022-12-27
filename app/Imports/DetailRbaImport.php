<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\Akun;
use App\Models\DetailRba;

class DetailRbaImport implements ToCollection
{
    private $id_rba;

    public function __construct($id_rba)
    {
        $this->id_rba = $id_rba; 
    }

    public function collection(Collection $rows)
    {
        foreach($rows AS $n=>$r) {
            if($n > 0 AND !is_null($r[5])) {
                $akun = Akun::where('keterangan', $r[0])->whereNull('deleted_at')->first();
                if(!is_null($akun)) {
                    DetailRba::create([
                        'id_detail_rba' => guid(),
                        'id_rba'        => $this->id_rba,
                        'id_akun'       => $akun->id_akun,
                        'vol'           => $r[2],
                        'satuan'        => (is_null($r[3])) ? '-' : $r[3],
                        'indikator'     => $r[4],
                        'tarif'         => $r[5],
                        'total'         => ($r[2] * $r[4] * $r[5]),
                        'created_at'    => now(),
                        'a_setuju'      => 1,
                    ]);
                }
            }
        }
    }
}
