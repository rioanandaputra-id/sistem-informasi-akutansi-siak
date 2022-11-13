<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bku extends Model
{
    protected $table = 'bku';
    protected $primaryKey = 'id_bku';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_bagian',
        'id_laksana_kegiatan',
        'tanggal',
        'id_akun',
        'masuk',
        'keluar',
        'saldo',
        'created_at',
        'updated_at',
        'id_updater',
    ];
}
