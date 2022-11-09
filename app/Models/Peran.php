<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peran extends Model
{
    protected $table = 'siak.peran';
    protected $primaryKey = 'id_peran';
    public $keyType = 'string';
    protected $fillable = [
        'nm_peran',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
