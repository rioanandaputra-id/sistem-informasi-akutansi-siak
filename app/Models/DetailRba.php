<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRba extends Model
{
    protected $table = 'detail_rba';
    protected $primaryKey = 'id_detail_rba';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_rba',
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
