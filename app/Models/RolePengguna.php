<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePengguna extends Model
{
    protected $table = 'siak.role_pengguna';
    protected $primaryKey = 'id_role_pengguna';
    public $keyType = 'string';
    protected $fillable = [
        'id_pengguna',
        'id_peran',
        'a_aktif',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
