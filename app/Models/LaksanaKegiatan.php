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
        'id_laksana_kegiatan',
        'id_kegiatan_divisi',
        'tgl_ajuan',
        'urutan_laksana_kegiatan',
        'a_verif_bend_kegiatan',
        'id_verif_bend_kegiatan',
        'tgl_verif_bend_kegiatan',
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
