<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'siak.pengguna';
    protected $primaryKey = 'id_pengguna';
    public $keyType = 'string';
    protected $fillable = [
        'username',
        'password',
        'nm_pengguna',
        'jk',
        'alamat',
        'no_hp',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
