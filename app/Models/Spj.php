<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    protected $table = 'spj';
    protected $primaryKey = 'id_spj';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_laksana_kegiatan',
        'a_verif_bendahara_pengeluaran',
        'id_verif_bendahara_pengeluaran',
        'tgl_verif_bendahara_pengeluaran',
        'a_verif_kabag_keuangan',
        'id_verif_kabag_keuangan',
        'tgl_verif_kabag_keuangan',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
