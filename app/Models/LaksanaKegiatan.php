<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaksanaKegiatan extends Model
{
    protected $table = 'laksana_kegiatan';
    protected $primaryKey = 'id_laksana_kegiatan';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_kegiatan_divisi',
        'tgl_ajuan',
        'a_verif_kabag_keuangan',
        'id_verif_kabag_keuangan',
        'tgl_verif_kabag_keuangan',
        'catatan',
        'waktu_pelaksanaan',
        'waktu_selesai',
        'tahun',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
