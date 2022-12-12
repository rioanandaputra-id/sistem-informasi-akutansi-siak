<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSpj extends Model
{
    protected $table = 'detail_spj';
    protected $primaryKey = 'id_detail_spj';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_detail_spj',
        'id_spj',
        'id_detail_laksana_kegiatan',
        'id_akun',
        'total',
        'id_dokumen',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
