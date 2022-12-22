<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_dokumen',
        'nm_dokumen',
        'nm_asli_dokumen',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}
