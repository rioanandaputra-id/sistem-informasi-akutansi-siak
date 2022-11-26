<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailLaksanaKegiatan extends Model
{
    protected $table = 'detail_laksana_kegiatan';
    protected $primaryKey = 'id_detail_laksana_kegiatan';
    public $keyType = 'string';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_detail_laksana_kegiatan',
        'id_laksana_kegiatan',
        'id_detail_rba',
        'jumlah',
        'total',
        'created_at',
        'updated_at',
        'id_updater',
    ];
}
