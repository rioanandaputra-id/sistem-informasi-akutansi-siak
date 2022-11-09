<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpjKeg extends Model
{
    protected $table = 'siak.spj_keg';
    protected $primaryKey = 'id_spj_keg';
    public $keyType = 'string';
    protected $fillable = [
        'id_rab_keg',
        'uraian',
        'qty',
        'satuan',
        'total',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
