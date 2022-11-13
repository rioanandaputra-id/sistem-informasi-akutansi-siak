<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Misi extends Model
{
    protected $table = 'misi';
    protected $primaryKey = 'id_misi';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_misi',
        'nm_misi',
        'periode',
        'a_aktif',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
