<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depart extends Model
{
    protected $table = 'siak.depart';
    protected $primaryKey = 'id_depart';
    public $keyType = 'string';
    protected $fillable = [
        'nm_depart',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
