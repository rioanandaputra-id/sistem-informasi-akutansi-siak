<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanRutin extends Model
{
    protected $table = 'kegiatan_rutin';
    protected $primaryKey = 'id_kegiatan_rutin';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_kegiatan_rutin',
        'id_divisi',
        'tgl_buat',
        'tgl_submit',
        'periode',
        'nm_kegiatan',
        'a_aktif',
        'a_verif_rba',
        'tgl_verif_rba',
        'id_verif_rba',
        'catatan',
        'a_verif_kabag_keuangan',
        'id_verif_kabag_keuangan',
        'tgl_verif_kabag_keuangan',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
