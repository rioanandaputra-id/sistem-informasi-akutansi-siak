<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prog extends Model
{
    protected $table = 'siak.prog';
    protected $primaryKey = 'id_prog';
    public $keyType = 'string';
    protected $fillable = [
        'nm_prog',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
