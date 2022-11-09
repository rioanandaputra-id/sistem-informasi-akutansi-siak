<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabKeg extends Model
{
    protected $table = 'siak.rab_keg';
    protected $primaryKey = 'id_rab_keg';
    public $keyType = 'string';
    protected $fillable = [
        'id_depart_keg',
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
