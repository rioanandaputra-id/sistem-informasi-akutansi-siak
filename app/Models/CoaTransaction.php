<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaTransaction extends Model
{
    protected $table = 'siak.coa_transaction';
    protected $primaryKey = 'id_coa_transaction';
    public $keyType = 'string';
    protected $fillable = [
        'id_coa',
        'id_depart_keg',
        'total',
        'tgl',
        'a_keluar',
        'a_masuk',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
