<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanDivisi extends Model
{
    protected $table = 'kegiatan_divisi';
    protected $primaryKey = 'id_kegiatan_divisi';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_kegiatan_divisi',
        'id_divisi',
        'id_kegiatan',
        'a_verif_rba',
        'id_verif_rba',
        'catatan',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
