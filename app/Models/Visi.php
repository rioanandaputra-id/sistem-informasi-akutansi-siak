<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visi extends Model
{
    protected $table = 'visi';
    protected $primaryKey = 'id_visi';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_visi',
        'nm_visi',
        'periode',
        'a_aktif',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
