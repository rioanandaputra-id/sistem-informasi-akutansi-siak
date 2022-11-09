<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifKeg extends Model
{
    protected $table = 'siak.verif_keg';
    protected $primaryKey = 'id_verif_keg';
    public $keyType = 'string';
    protected $fillable = [
        'id_depart_keg',
        'id_verif',
        'tgl_verif',
        'catatan',
        'a_verif',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
