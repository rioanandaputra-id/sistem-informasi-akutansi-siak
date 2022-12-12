<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKegiatanRutin extends Model
{
    protected $table = 'detail_kegiatan_rutin';
    protected $primaryKey = 'id_detail_kegiatan_rutin';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_detail_kegiatan_rutin',
        'id_kegiatan_rutin',
        'id_akun',
        'vol',
        'satuan',
        'indikator',
        'tarif',
        'total',
        'a_setuju',
        'created_at',
        'updated_at',
    ];
}
