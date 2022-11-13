<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';
    protected $primaryKey = 'id_program';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_program',
        'nm_program',
        'periode',
        'a_aktif',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
        'id_misi',
    ];
}
