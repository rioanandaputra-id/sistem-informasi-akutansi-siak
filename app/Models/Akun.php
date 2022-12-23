<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'id_akun';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_akun',
        'no_akun_induk',
        'elemen',
        'sub_elemen',
        'jenis',
        'no_akun',
        'nm_akun',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
