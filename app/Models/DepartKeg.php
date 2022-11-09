<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartKeg extends Model
{
    protected $table = 'siak.depart_keg';
    protected $primaryKey = 'id_depart_keg';
    public $keyType = 'string';
    protected $fillable = [
        'id_depart',
        'id_prog',
        'id_keg',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
