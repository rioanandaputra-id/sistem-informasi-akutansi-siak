<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Akun;
use App\Models\DetailRba;

class DetailRbaImport implements ToModel, WithHeadingRow
{
    private $idRba; 

    public function __construct($id_rba)
    {
        $this->idRba = $id_rba; 
    }

    public function model(array $row)
    {
        $akun = Akun::where('keterangan', $row['KODE'])->whereNull('deleted_at')->first();
        return new DetailRba([
            'id_detail_rba' => \Str::uuid(),
            'id_rba'        => $this->idRba,
            'id_akun'       => $akun->id_akun,
            'nm_akun'       => $row['NAME'],
            'vol'           => $row['VOLUME'],
            'satuan'        => $row['SATUAN'],
            'indikator'     => $row['INDIKATOR'],
            'tarif'         => $row['TARIF'],
            'total'         => $row['VOLUME'] * $row['INDIKATOR'] * $row['TARIF'],
            'created_at'    => now(),
            'a_setuju'      => 1,
        ]);
    }
}
