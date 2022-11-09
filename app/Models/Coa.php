<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $table = 'siak.coa';
    protected $primaryKey = 'id_coa';
    public $keyType = 'string';
    protected $fillable = [
        'id_sub_coa',
        'nm_coa',
        'uraian',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
