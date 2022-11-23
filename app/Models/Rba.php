<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rba extends Model
{
    protected $table = 'rba';
    protected $primaryKey = 'id_rba';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_rba',
        'tgl_buat',
        'tgl_submit',
        'catatan',
        'id_kegiatan_divisi',
        'a_verif_rba',
        'id_verif_rba',
        'tgl_verif_rba',
        'a_verif_wilayah',
        'id_verif_wilayah',
        'tgl_verif_wilayah',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
