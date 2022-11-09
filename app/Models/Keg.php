<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keg extends Model
{
    protected $table = 'siak.keg';
    protected $primaryKey = 'id_keg';
    public $keyType = 'string';
    protected $fillable = [
        'nm_keg',
        'tgl_mulai',
        'tgl_selesai',
        'a_aktif',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
